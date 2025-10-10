<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\ProfesorMateria;
use App\Models\Inscripcion;
use App\Models\Asistencia;
use App\Models\NotaTemporal;
use App\Models\Materia;
use App\Models\User;

class ExpedienteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Debug temporal
        \Log::info('Expediente Index', [
            'user_tipo' => $user->tipo,
            'user_id' => $user->id,
            'user_name' => $user->nombre
        ]);

        // Profesor: mostrar panel de opciones (sin métricas, están en Dashboard)
        if ($user->tipo == 3) {
            \Log::info('Renderizando ProfesorPanel');
            return Inertia::render('Expediente/ProfesorPanel');
        }

        // Admin: mostrar panel administrativo completo
        if ($user->tipo == 1) {
            \Log::info('Renderizando AdminPanel');
            return $this->mostrarPanelAdmin($request);
        }

        // Alumno: redirigir a su propio expediente
        if ($user->tipo == 4 && $user->alumno_id) {
            \Log::info('Redirigiendo a expediente del alumno');
            return redirect()->route('expediente.show', $user->alumno_id);
        }

        \Log::warning('Usuario sin acceso a expediente', ['tipo' => $user->tipo]);
        abort(403, 'No autorizado para acceder a esta sección');
    }

    private function mostrarPanelAdmin(Request $request)
    {
        // Cargar todas las carreras
        $carreras = Carrera::all();

        // Query para materias
        $queryMaterias = Materia::with('carrera');

        // Filtros para materias
        if ($request->filled('carrera')) {
            $queryMaterias->where('carrera', $request->carrera);
        }
        if ($request->filled('anno')) {
            $queryMaterias->where('anno', $request->anno);
        }
        if ($request->filled('semestre')) {
            $queryMaterias->where('semestre', $request->semestre);
        }
        if ($request->filled('buscar')) {
            $queryMaterias->where('nombre', 'like', "%{$request->buscar}%");
        }

        $materias = $queryMaterias->orderBy('nombre', 'asc')
                                  ->paginate(15)
                                  ->withQueryString();

        // Query para usuarios (profesores y alumnos)
        $queryUsuarios = User::with(['alumno', 'profesor']);

        // Filtros para usuarios
        if ($request->filled('tipo')) {
            $queryUsuarios->where('tipo', $request->tipo);
        }
        if ($request->filled('activo')) {
            $queryUsuarios->where('activo', $request->activo);
        }
        if ($request->filled('buscar_usuario')) {
            $search = $request->buscar_usuario;
            $queryUsuarios->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $usuarios = $queryUsuarios->orderBy('nombre', 'asc')
                                  ->paginate(15)
                                  ->withQueryString();

        // Debug temporal
        \Log::info('AdminPanel Data', [
            'materias_count' => $materias->total(),
            'usuarios_count' => $usuarios->total(),
            'carreras_count' => $carreras->count()
        ]);

        return Inertia::render('Expediente/AdminPanel', [
            'materias' => $materias,
            'carreras' => $carreras,
            'usuarios' => $usuarios,
            'filtrosMaterias' => $request->only(['carrera', 'anno', 'semestre', 'buscar']),
            'filtrosUsuarios' => $request->only(['tipo', 'activo', 'buscar_usuario']),
        ]);
    }

    private function listarAlumnos(Request $request)
    {
        $query = Alumno::with('carrera');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        if ($request->filled('carrera')) {
            $query->where('carrera', $request->carrera);
        }

        $alumnos = $query->orderBy('apellido', 'asc')
                        ->orderBy('nombre', 'asc')
                        ->paginate(15)
                        ->withQueryString();

        $carreras = Carrera::all();

        return Inertia::render('Expediente/Index', [
            'alumnos' => $alumnos,
            'carreras' => $carreras,
            'filters' => $request->only(['search', 'carrera'])
        ]);
    }

    public function show($id)
    {
        $alumno = Alumno::with([
            'carreraRelacion',
            'materiasCursadas.materia'
        ])->findOrFail($id);

        // Obtener el ID de carrera del alumno
        $carreraId = $alumno->getAttributes()['carrera'] ?? null;

        // Obtener materias cursadas
        $materiasCursadas = $alumno->materiasCursadas()
            ->with('materia')
            ->when($carreraId, function($query) use ($carreraId) {
                return $query->where('carrera', $carreraId);
            })
            ->get();

        // Agrupar por año
        $historialPorAnio = $materiasCursadas->groupBy(function($item) {
            return $item->materia->anno ?? 0;
        })->map(function($materias) {
            return $materias->map(function($materiaCursada) {
                return [
                    'id' => $materiaCursada->id,
                    'materia_nombre' => $materiaCursada->materia->nombre ?? 'Sin nombre',
                    'rendida' => $materiaCursada->nota ? true : false,
                    'cursada' => $materiaCursada->regular ? true : false,
                    'libre' => $materiaCursada->libre ? true : false,
                    'equivalencia' => $materiaCursada->equivalencia ? true : false,
                    'calificacion_cursada' => $materiaCursada->regular ? 'Regular' : null,
                    'calificacion_rendida' => $materiaCursada->nota ? $materiaCursada->nota : null,
                    'nota_final' => $materiaCursada->nota,
                    'fecha' => $materiaCursada->fecha ? \Carbon\Carbon::parse($materiaCursada->fecha)->format('d/m/Y') : null,
                ];
            })->values();
        });

        // Calcular estadísticas
        $totalMaterias = $materiasCursadas->count();
        $aprobadas = $materiasCursadas->where('nota', '>=', 4)->count();
        $regulares = $materiasCursadas->where('regular', 1)->where('nota', null)->count();

        // Calcular promedio de materias aprobadas
        $notasAprobadas = $materiasCursadas->where('nota', '>=', 4)->pluck('nota')->filter();
        $promedio = $notasAprobadas->count() > 0 ? round($notasAprobadas->avg(), 2) : 0;

        // Calcular porcentaje de avance (basado en aprobadas)
        $porcentajeAvance = $totalMaterias > 0 ? round(($aprobadas / $totalMaterias) * 100, 1) : 0;

        $estadisticas = [
            'total_materias' => $totalMaterias,
            'aprobadas' => $aprobadas,
            'regulares' => $regulares,
            'promedio' => $promedio,
            'porcentaje_avance' => $porcentajeAvance
        ];

        return Inertia::render('Expediente/Show', [
            'alumno' => [
                'id' => $alumno->id,
                'dni' => $alumno->dni,
                'nombre_completo' => $alumno->apellido . ', ' . $alumno->nombre,
                'carrera' => $alumno->carreraRelacion ? $alumno->carreraRelacion->nombre : 'Sin carrera',
            ],
            'historialPorAnio' => $historialPorAnio,
            'estadisticas' => $estadisticas
        ]);
    }

    public function buscarPorDni(Request $request)
    {
        $request->validate([
            'dni' => 'required|string'
        ]);

        // Usar carreraRelacion para evitar conflicto con el atributo 'carrera'
        $alumno = Alumno::with(['carreraRelacion'])->where('dni', $request->dni)->first();

        if (!$alumno) {
            return response()->json([
                'error' => 'No se encontró ningún alumno con ese DNI'
            ], 404);
        }

        // Obtener el ID de carrera del alumno
        $carreraId = $alumno->getAttributes()['carrera'] ?? null;

        // Obtener el historial académico agrupado por año
        $historialAcademico = $alumno->materiasCursadas()
            ->with('materia')
            ->when($carreraId, function($query) use ($carreraId) {
                return $query->where('carrera', $carreraId);
            })
            ->get()
            ->groupBy(function($item) {
                return $item->materia->anno ?? 'Sin año';
            })
            ->map(function($materias) {
                return $materias->map(function($materiaCursada) {
                    return [
                        'materia' => $materiaCursada->materia->nombre ?? 'Sin nombre',
                        'regular' => $materiaCursada->regular ? '✓' : '',
                        'promocional' => $materiaCursada->promocional ? '✓' : '',
                        'equivalencia' => $materiaCursada->equivalencia ? '✓' : '',
                        'libre' => $materiaCursada->libre ? '✓' : '',
                        'nota' => $materiaCursada->nota ?? '',
                        'fecha' => $materiaCursada->fecha ? \Carbon\Carbon::parse($materiaCursada->fecha)->format('d/m/Y') : '',
                        'libro' => $materiaCursada->libro ?? '',
                        'folio' => $materiaCursada->folio ?? ''
                    ];
                });
            });

        return response()->json([
            'alumno' => [
                'dni' => $alumno->dni,
                'apellido' => $alumno->apellido,
                'nombre' => $alumno->nombre,
                'anio_ingreso' => $alumno->anio_ingreso ?? 'N/A',
                'carrera' => $alumno->carreraRelacion ? $alumno->carreraRelacion->nombre : 'Sin carrera'
            ],
            'historial' => $historialAcademico
        ]);
    }

    public function obtenerAlumnosProfesor(Request $request)
    {
        $user = $request->user();

        if (!$user->profesor_id) {
            return response()->json([
                'error' => 'No se encontró un perfil de profesor asociado'
            ], 404);
        }

        // Obtener materias del profesor
        $materias = ProfesorMateria::with(['materiaRelacion', 'carreraRelacion'])
            ->where('profesor', $user->profesor_id)
            ->get();

        // Agrupar alumnos por materia
        $alumnosPorMateria = [];

        foreach ($materias as $materia) {
            $inscripciones = Inscripcion::with(['alumno'])
                ->where('materia_id', $materia->materia)
                ->where('carrera_id', $materia->carrera)
                ->where('estado', 'confirmada')
                ->get();

            $alumnos = $inscripciones->map(function($inscripcion) {
                return [
                    'id' => $inscripcion->alumno->id,
                    'dni' => $inscripcion->alumno->dni,
                    'apellido' => $inscripcion->alumno->apellido,
                    'nombre' => $inscripcion->alumno->nombre,
                    'email' => $inscripcion->alumno->email ?? 'N/A',
                ];
            })->sortBy('apellido')->values();

            $alumnosPorMateria[] = [
                'materia_id' => $materia->id,
                'materia' => $materia->materiaRelacion->nombre ?? 'Sin nombre',
                'carrera' => $materia->carreraRelacion->nombre ?? 'Sin carrera',
                'cursado' => $materia->cursado ?? 'N/A',
                'division' => $materia->division ?? 'N/A',
                'alumnos' => $alumnos,
                'total_alumnos' => $alumnos->count()
            ];
        }

        return response()->json([
            'materias' => $alumnosPorMateria
        ]);
    }

    public function guardarAsistencia(Request $request)
    {
        $request->validate([
            'profesor_materia_id' => 'required|exists:tbl_profesor_tiene_materias,id',
            'fecha' => 'required|date',
            'asistencias' => 'required|array',
            'asistencias.*.alumno_id' => 'required|exists:tbl_alumnos,id',
            'asistencias.*.estado' => 'required|in:presente,ausente,tarde',
            'asistencias.*.observaciones' => 'nullable|string'
        ]);

        $user = $request->user();
        $registros = [];

        foreach ($request->asistencias as $asistencia) {
            // Verificar si ya existe asistencia para este alumno en esta fecha
            $existe = Asistencia::where('profesor_materia_id', $request->profesor_materia_id)
                ->where('alumno_id', $asistencia['alumno_id'])
                ->where('fecha', $request->fecha)
                ->first();

            if ($existe) {
                // Actualizar registro existente
                $existe->update([
                    'estado' => $asistencia['estado'],
                    'observaciones' => $asistencia['observaciones'] ?? null,
                ]);
                $registros[] = $existe;
            } else {
                // Crear nuevo registro
                $registros[] = Asistencia::create([
                    'profesor_materia_id' => $request->profesor_materia_id,
                    'alumno_id' => $asistencia['alumno_id'],
                    'fecha' => $request->fecha,
                    'estado' => $asistencia['estado'],
                    'observaciones' => $asistencia['observaciones'] ?? null,
                    'registrado_por' => $user->id
                ]);
            }
        }

        return response()->json([
            'message' => 'Asistencia guardada correctamente',
            'registros' => count($registros)
        ], 200);
    }

    public function guardarNotas(Request $request)
    {
        $request->validate([
            'profesor_materia_id' => 'required|exists:tbl_profesor_tiene_materias,id',
            'tipo_evaluacion' => 'required|string|max:100',
            'fecha' => 'required|date',
            'notas' => 'required|array',
            'notas.*.alumno_id' => 'required|exists:tbl_alumnos,id',
            'notas.*.nota' => 'required|numeric|min:1|max:10',
            'notas.*.observaciones' => 'nullable|string'
        ]);

        $user = $request->user();
        $registros = [];

        foreach ($request->notas as $nota) {
            $registros[] = NotaTemporal::create([
                'profesor_materia_id' => $request->profesor_materia_id,
                'alumno_id' => $nota['alumno_id'],
                'nota' => $nota['nota'],
                'tipo_evaluacion' => $request->tipo_evaluacion,
                'estado' => 'pendiente',
                'fecha' => $request->fecha,
                'observaciones' => $nota['observaciones'] ?? null,
                'registrado_por' => $user->id
            ]);
        }

        return response()->json([
            'message' => 'Notas guardadas correctamente. Pendientes de aprobación.',
            'registros' => count($registros)
        ], 200);
    }

    public function guardarAsistenciaFinal(Request $request)
    {
        $request->validate([
            'profesor_materia_id' => 'required|exists:tbl_profesor_tiene_materias,id',
            'total_clases' => 'required|integer|min:1',
            'asistencias' => 'required|array',
            'asistencias.*.alumno_id' => 'required|exists:tbl_alumnos,id',
            'asistencias.*.presentes' => 'required|integer|min:0',
            'asistencias.*.ausentes' => 'required|integer|min:0',
            'asistencias.*.tardes' => 'required|integer|min:0',
            'asistencias.*.observaciones' => 'nullable|string'
        ]);

        $user = $request->user();
        $registros = [];
        $fecha = now()->format('Y-m-d'); // Usar fecha actual para el registro

        foreach ($request->asistencias as $asistencia) {
            // Verificar si ya existe asistencia final para este alumno
            $existe = Asistencia::where('profesor_materia_id', $request->profesor_materia_id)
                ->where('alumno_id', $asistencia['alumno_id'])
                ->where('tipo_carga', 'final')
                ->first();

            if ($existe) {
                // Actualizar registro existente
                $existe->update([
                    'presentes' => $asistencia['presentes'],
                    'ausentes' => $asistencia['ausentes'],
                    'tardes' => $asistencia['tardes'],
                    'total_clases' => $request->total_clases,
                    'observaciones' => $asistencia['observaciones'] ?? null,
                ]);
                $registros[] = $existe;
            } else {
                // Crear nuevo registro de asistencia final
                $registros[] = Asistencia::create([
                    'profesor_materia_id' => $request->profesor_materia_id,
                    'alumno_id' => $asistencia['alumno_id'],
                    'fecha' => $fecha,
                    'estado' => 'presente', // No se usa para tipo final
                    'tipo_carga' => 'final',
                    'presentes' => $asistencia['presentes'],
                    'ausentes' => $asistencia['ausentes'],
                    'tardes' => $asistencia['tardes'],
                    'total_clases' => $request->total_clases,
                    'observaciones' => $asistencia['observaciones'] ?? null,
                    'registrado_por' => $user->id
                ]);
            }
        }

        return response()->json([
            'message' => 'Asistencia final guardada correctamente',
            'registros' => count($registros)
        ], 200);
    }

    public function guardarNotasFinales(Request $request)
    {
        $request->validate([
            'profesor_materia_id' => 'required|exists:tbl_profesor_tiene_materias,id',
            'fecha' => 'required|date',
            'notas' => 'required|array',
            'notas.*.alumno_id' => 'required|exists:tbl_alumnos,id',
            'notas.*.nota' => 'required|numeric|min:1|max:10',
            'notas.*.observaciones' => 'nullable|string'
        ]);

        $user = $request->user();
        $registros = [];

        foreach ($request->notas as $nota) {
            // Verificar si ya existe nota final para este alumno
            $existe = NotaTemporal::where('profesor_materia_id', $request->profesor_materia_id)
                ->where('alumno_id', $nota['alumno_id'])
                ->where('tipo_evaluacion', 'Final')
                ->first();

            if ($existe) {
                // Actualizar registro existente
                $existe->update([
                    'nota' => $nota['nota'],
                    'fecha' => $request->fecha,
                    'observaciones' => $nota['observaciones'] ?? null,
                    'estado' => 'pendiente', // Volver a pendiente si se modifica
                ]);
                $registros[] = $existe;
            } else {
                // Crear nuevo registro
                $registros[] = NotaTemporal::create([
                    'profesor_materia_id' => $request->profesor_materia_id,
                    'alumno_id' => $nota['alumno_id'],
                    'nota' => $nota['nota'],
                    'tipo_evaluacion' => 'Final',
                    'estado' => 'pendiente',
                    'fecha' => $request->fecha,
                    'observaciones' => $nota['observaciones'] ?? null,
                    'registrado_por' => $user->id
                ]);
            }
        }

        return response()->json([
            'message' => 'Notas finales guardadas correctamente. Pendientes de aprobación.',
            'registros' => count($registros)
        ], 200);
    }
}