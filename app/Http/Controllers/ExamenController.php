<?php

namespace App\Http\Controllers;

use App\Models\Alumnos;
use App\Models\Mesa_1;
use App\Models\Mesa_2;
use App\Models\Carrera;
use App\Models\Materias;
use App\Models\Alumno_materia;
use App\Models\Correlativas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamenController extends Controller
{
    public function identificar()
    {
        return view('identificar');
    }

    public function buscarAlumno(Request $request)
    {
        $request->validate([
            'dni' => 'required|numeric'
        ]);

        $alumno = Alumnos::where('dni', $request->dni)->first();

        if (!$alumno) {
            return back()->with('error', 'Alumno no encontrado.');
        }

        return redirect()->route('examenes.seleccionar-mesa', ['idAlumno' => $alumno->id]);
    }

    public function materias($idAlumno)
    {
        $alumno = Alumnos::findOrFail($idAlumno);

        $mesaSeleccionada = session('mesa_seleccionada', 1);

        $carrera = Carrera::find($alumno->carrera);

        $todasLasMaterias = Materias::where('carrera', $alumno->carrera)->get();

        $materiasAprobadas = Alumno_materia::where('alumno', $idAlumno)
            ->where('nota', '>=', 7)
            ->pluck('materia')
            ->toArray();

        $materiasRegulares = Alumno_materia::where('alumno', $idAlumno)
            ->where('nota', '>=', 4)
            ->where('nota', '<', 7)
            ->pluck('materia')
            ->toArray();

        $materias = $todasLasMaterias->filter(function($materia) use ($materiasAprobadas) {
            return !in_array($materia->id, $materiasAprobadas);
        });

        return view('materias', compact('alumno', 'carrera', 'materias', 'materiasAprobadas', 'materiasRegulares', 'mesaSeleccionada'));
    }

    public function validarInscripcion($idAlumno, $idMateria)
    {
        $yaAprobada = Alumno_materia::where('alumno', $idAlumno)
            ->where('materia', $idMateria)
            ->where('nota', '>=', 7)
            ->exists();

        if ($yaAprobada) {
            return response()->json([
                'puede_rendir' => false,
                'mensaje' => 'Esta materia ya está aprobada.'
            ]);
        }

        $correlativas = Correlativas::where('materia', $idMateria)->get();

        if ($correlativas->isEmpty()) {
            return response()->json([
                'puede_rendir' => true,
                'mensaje' => 'No tiene correlativas requeridas.'
            ]);
        }

        $correlativasCumplidas = [];
        $correlativasFaltantes = [];

        foreach ($correlativas as $correlativa) {
            $aprobada = Alumno_materia::where('alumno', $idAlumno)
                ->where('materia', $correlativa->correlativa)
                ->where('nota', '>=', 7)
                ->exists();

            $materiaCorrelativa = Materias::find($correlativa->correlativa);
            $nombreMateria = $materiaCorrelativa ? $materiaCorrelativa->nombre : "ID: {$correlativa->correlativa}";

            if ($aprobada) {
                $correlativasCumplidas[] = $nombreMateria;
            } else {
                $correlativasFaltantes[] = $nombreMateria;
            }
        }

        if (!empty($correlativasFaltantes)) {
            $mensaje = "Faltan aprobar las siguientes correlativas: " . implode(', ', $correlativasFaltantes);

            if (!empty($correlativasCumplidas)) {
                $mensaje .= " | Cumplidas: " . implode(', ', $correlativasCumplidas);
            }

            return response()->json([
                'puede_rendir' => false,
                'mensaje' => $mensaje,
                'detalle' => [
                    'cumplidas' => $correlativasCumplidas,
                    'faltantes' => $correlativasFaltantes
                ]
            ]);
        }

        return response()->json([
            'puede_rendir' => true,
            'mensaje' => 'Todas las correlativas están cumplidas.',
            'detalle' => [
                'cumplidas' => $correlativasCumplidas,
                'faltantes' => []
            ]
        ]);
    }

    public function inscribir(Request $request)
    {
        $request->validate([
            'alumno_id' => 'required|exists:tbl_alumnos,id',
            'materia_id' => 'required|array',
            'materia_id.*' => 'exists:tbl_materias,id',
            'mesa' => 'required|in:1,2'
        ]);

        $alumno = Alumnos::find($request->alumno_id);

        if ($request->mesa == 1) {
            $ultimoId = DB::table('mesa_agosto_1_llamado')->max('id');
        } else {
            $ultimoId = DB::table('mesa_agosoto_2_llamado')->max('id');
        }

        $idBase = $ultimoId ? $ultimoId + 1 : 1;
        $idActual = $idBase;
        $materiasInscritas = 0;

        foreach ($request->materia_id as $materiaId) {
            $validacion = $this->validarInscripcion($request->alumno_id, $materiaId);
            $data = json_decode($validacion->getContent(), true);

            if (!$data['puede_rendir']) {
                continue;
            }

            $fechaActual = now()->format('Y-m-d H:i:s');

            if ($request->mesa == 1) {
                DB::table('mesa_agosto_1_llamado')->insert([
                    'id' => $idActual++,
                    'alumno' => $alumno->dni,
                    'materia' => $materiaId,
                    'fecha' => $fechaActual,
                    'estado' => null,
                    'observacion' => 'Inscripción automática',
                    'fecha_cancelacion' => null
                ]);
            } else {
                DB::table('mesa_agosoto_2_llamado')->insert([
                    'id' => $idActual++,
                    'alumno' => $alumno->dni,
                    'materia' => $materiaId,
                    'fecha' => $fechaActual,
                    'estado' => null,
                    'observacion' => 'Inscripción automática',
                    'fecha_cancelacion' => null
                ]);
            }

            $materiasInscritas++;
        }

        return redirect()->route('materias', ['idAlumno' => $request->alumno_id])
                        ->with('success', 'Inscripción exitosa a ' . $materiasInscritas . ' materia(s)');
    }

    public function seleccionarMesa($idAlumno)
    {
        $alumno = Alumnos::findOrFail($idAlumno);
        return view('seleccionar-mesa', compact('alumno'));
    }

    public function procesarMesa(Request $request)
    {
        $request->validate([
            'alumno_id' => 'required|exists:tbl_alumnos,id',
            'mesa' => 'required|in:1,2'
        ]);

        session(['mesa_seleccionada' => $request->mesa]);

        return redirect()->route('materias', ['idAlumno' => $request->alumno_id])
                        ->with('success', 'Mesa cambiada exitosamente a ' . $request->mesa . '° llamado');
    }

    public function verInscripciones($idAlumno)
    {
        $alumno = Alumnos::findOrFail($idAlumno);

        $inscripcionesMesa1 = DB::table('mesa_agosto_1_llamado')
            ->where('alumno', $alumno->dni)
            ->join('tbl_materias', 'mesa_agosto_1_llamado.materia', '=', 'tbl_materias.id')
            ->select('mesa_agosto_1_llamado.*', 'tbl_materias.nombre as materia_nombre')
            ->get()
            ->map(function($item) {
                $item->mesa = 1;
                return $item;
            });

        $inscripcionesMesa2 = DB::table('mesa_agosoto_2_llamado')
            ->where('alumno', $alumno->dni)
            ->join('tbl_materias', 'mesa_agosoto_2_llamado.materia', '=', 'tbl_materias.id')
            ->select('mesa_agosoto_2_llamado.*', 'tbl_materias.nombre as materia_nombre')
            ->get()
            ->map(function($item) {
                $item->mesa = 2;
                return $item;
            });

        $inscripciones = $inscripcionesMesa1->merge($inscripcionesMesa2);

        return view('inscripciones', compact('alumno', 'inscripciones'));
    }

    public function eliminarInscripcion($mesa, $id)
    {
        if ($mesa == 1) {
            DB::table('mesa_agosto_1_llamado')->where('id', $id)->delete();
        } else {
            DB::table('mesa_agosoto_2_llamado')->where('id', $id)->delete();
        }

        return back()->with('success', 'Inscripción eliminada correctamente');
    }
}