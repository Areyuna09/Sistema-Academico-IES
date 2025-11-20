<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Profesor;
use App\Models\Pais;
use App\Models\Provincia;
use App\Models\Sexo;
use App\Enums\TipoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;

class UsuariosController extends Controller
{
    /**
     * Mostrar listado de usuarios
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtrar por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtrar por estado
        if ($request->filled('activo')) {
            $query->where('activo', $request->activo === '1');
        }

        // Buscar por DNI, nombre o email
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('dni', 'like', "%{$buscar}%")
                  ->orWhere('nombre', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        $usuarios = $query
            ->with(['alumno', 'profesor'])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Usuarios/Index', [
            'usuarios' => $usuarios,
            'filtros' => $request->only(['tipo', 'activo', 'buscar']),
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        // Obtener alumnos sin usuario asignado
        $alumnosSinUsuario = Alumno::whereDoesntHave('user')
            ->select('id', 'dni', 'apellido', 'nombre', 'email', 'telefono', 'celular')
            ->orderBy('apellido')
            ->get()
            ->map(function($alumno) {
                return [
                    'id' => $alumno->id,
                    'nombre_completo' => $alumno->nombre_completo,
                    'dni' => $alumno->dni,
                    'email' => $alumno->email,
                    'telefono' => $alumno->celular ?: ($alumno->telefono ?: null),
                ];
            });

        // Obtener profesores sin usuario asignado
        $profesoresSinUsuario = Profesor::whereDoesntHave('user')
            ->select('id', 'dni', 'apellido', 'nombre', 'email')
            ->orderBy('apellido')
            ->get()
            ->map(function($profesor) {
                return [
                    'id' => $profesor->id,
                    'nombre_completo' => $profesor->nombre_completo,
                    'dni' => $profesor->dni,
                    'email' => $profesor->email,
                ];
            });

        // Obtener catálogos
        $paises = Pais::where('activo', true)->get(['id', 'nombre']);
        $provincias = Provincia::where('activo', true)->orderBy('nombre')->get(['id', 'nombre', 'pais_id']);
        $sexos = Sexo::where('activo', true)->get(['id', 'nombre']);

        return Inertia::render('Admin/Usuarios/Create', [
            'alumnosSinUsuario' => $alumnosSinUsuario,
            'profesoresSinUsuario' => $profesoresSinUsuario,
            'paises' => $paises,
            'provincias' => $provincias,
            'sexos' => $sexos,
        ]);
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni' => ['required', 'string', 'max:10', 'regex:/^[0-9]+$/', 'unique:tbl_usuarios,dni'],
            'nombre' => ['required', 'string', 'max:100', 'regex:/^.*[a-zA-ZáéíóúÁÉÍÓÚñÑ]+.*$/'],
            'email' => 'required|email|max:100|unique:tbl_usuarios,email',
            'tipo' => ['required', 'integer', Rule::in([TipoUsuario::ADMIN, TipoUsuario::PROFESOR, TipoUsuario::ALUMNO])],
            'password' => 'required|string|min:6|confirmed',
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s\-\+\(\)]+$/'],
            'alumno_id' => 'nullable|exists:tbl_alumnos,id',
            'profesor_id' => 'nullable|exists:tbl_profesores,id',
            'pais' => 'nullable|exists:tbl_paises,id',
            'provincia' => 'nullable|exists:tbl_provincias,id',
            'sexo' => 'nullable|exists:tbl_sexos,id',
            'activo' => 'boolean',
        ], [
            'dni.regex' => 'El DNI debe contener solo números.',
            'nombre.regex' => 'El nombre debe contener al menos una letra.',
            'telefono.regex' => 'El teléfono solo puede contener números, espacios, guiones, paréntesis y signo +.',
        ]);

        // Validar que solo se vincule a alumno O profesor, no ambos
        if ($validated['alumno_id'] && $validated['profesor_id']) {
            return back()->withErrors(['alumno_id' => 'No se puede vincular a un alumno y un profesor al mismo tiempo.']);
        }

        // Si seleccionó alumno, validar que el tipo sea ALUMNO
        if ($validated['alumno_id'] && $validated['tipo'] != TipoUsuario::ALUMNO) {
            return back()->withErrors(['tipo' => 'Si vincula a un alumno, el tipo debe ser "Alumno".']);
        }

        // Si seleccionó profesor, validar que el tipo sea PROFESOR
        if ($validated['profesor_id'] && $validated['tipo'] != TipoUsuario::PROFESOR) {
            return back()->withErrors(['tipo' => 'Si vincula a un profesor, el tipo debe ser "Profesor".']);
        }

        // Crear usuario
        $usuario = User::create([
            'dni' => $validated['dni'],
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'tipo' => $validated['tipo'],
            'clave' => Hash::make($validated['password']),
            'telefono' => $validated['telefono'] ?? null,
            'alumno_id' => $validated['alumno_id'] ?? null,
            'profesor_id' => $validated['profesor_id'] ?? null,
            'pais' => $validated['pais'] ?? 1, // Por defecto Argentina
            'provincia' => $validated['provincia'] ?? 1, // Por defecto San Juan
            'sexo' => $validated['sexo'] ?? null,
            'activo' => $validated['activo'] ?? true,
            'usuario' => $validated['dni'], // Por defecto, el usuario es el DNI
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(User $usuario)
    {
        $usuario->load(['alumno', 'profesor']);

        // Obtener alumnos sin usuario (incluyendo el actual si existe)
        $alumnosSinUsuario = Alumno::where(function($q) use ($usuario) {
                $q->whereDoesntHave('user')
                  ->orWhere('id', $usuario->alumno_id);
            })
            ->select('id', 'dni', 'apellido', 'nombre', 'email', 'telefono', 'celular')
            ->orderBy('apellido')
            ->get()
            ->map(function($alumno) {
                return [
                    'id' => $alumno->id,
                    'nombre_completo' => $alumno->nombre_completo,
                    'dni' => $alumno->dni,
                    'email' => $alumno->email,
                    'telefono' => $alumno->celular ?: ($alumno->telefono ?: null),
                ];
            });

        // Obtener profesores sin usuario (incluyendo el actual si existe)
        $profesoresSinUsuario = Profesor::where(function($q) use ($usuario) {
                $q->whereDoesntHave('user')
                  ->orWhere('id', $usuario->profesor_id);
            })
            ->select('id', 'dni', 'apellido', 'nombre', 'email')
            ->orderBy('apellido')
            ->get()
            ->map(function($profesor) {
                return [
                    'id' => $profesor->id,
                    'nombre_completo' => $profesor->nombre_completo,
                    'dni' => $profesor->dni,
                    'email' => $profesor->email,
                ];
            });

        // Obtener catálogos
        $paises = Pais::where('activo', true)->get(['id', 'nombre']);
        $provincias = Provincia::where('activo', true)->orderBy('nombre')->get(['id', 'nombre', 'pais_id']);
        $sexos = Sexo::where('activo', true)->get(['id', 'nombre']);

        return Inertia::render('Admin/Usuarios/Edit', [
            'usuario' => [
                'id' => $usuario->id,
                'dni' => $usuario->dni,
                'nombre' => $usuario->nombre,
                'email' => $usuario->email,
                'tipo' => $usuario->tipo,
                'telefono' => $usuario->telefono,
                'activo' => $usuario->activo,
                'alumno_id' => $usuario->alumno_id,
                'profesor_id' => $usuario->profesor_id,
                'pais' => $usuario->pais,
                'provincia' => $usuario->provincia,
                'sexo' => $usuario->sexo,
                'alumno' => $usuario->alumno ? [
                    'id' => $usuario->alumno->id,
                    'nombre_completo' => $usuario->alumno->nombre_completo,
                ] : null,
                'profesor' => $usuario->profesor ? [
                    'id' => $usuario->profesor->id,
                    'nombre_completo' => $usuario->profesor->nombre_completo,
                ] : null,
            ],
            'alumnosSinUsuario' => $alumnosSinUsuario,
            'profesoresSinUsuario' => $profesoresSinUsuario,
            'paises' => $paises,
            'provincias' => $provincias,
            'sexos' => $sexos,
        ]);
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'dni' => ['required', 'string', 'max:10', 'regex:/^[0-9]+$/', Rule::unique('tbl_usuarios')->ignore($usuario->id)],
            'nombre' => ['required', 'string', 'max:100', 'regex:/^.*[a-zA-ZáéíóúÁÉÍÓÚñÑ]+.*$/'],
            'email' => ['required', 'email', 'max:100', Rule::unique('tbl_usuarios')->ignore($usuario->id)],
            'tipo' => ['required', 'integer', Rule::in([TipoUsuario::ADMIN, TipoUsuario::PROFESOR, TipoUsuario::ALUMNO])],
            'password' => 'nullable|string|min:6|confirmed',
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s\-\+\(\)]+$/'],
            'alumno_id' => 'nullable|exists:tbl_alumnos,id',
            'profesor_id' => 'nullable|exists:tbl_profesores,id',
            'pais' => 'nullable|exists:tbl_paises,id',
            'provincia' => 'nullable|exists:tbl_provincias,id',
            'sexo' => 'nullable|exists:tbl_sexos,id',
            'activo' => 'boolean',
        ], [
            'dni.regex' => 'El DNI debe contener solo números.',
            'nombre.regex' => 'El nombre debe contener al menos una letra.',
            'telefono.regex' => 'El teléfono solo puede contener números, espacios, guiones, paréntesis y signo +.',
        ]);

        // Validaciones de vinculación
        if ($validated['alumno_id'] && $validated['profesor_id']) {
            return back()->withErrors(['alumno_id' => 'No se puede vincular a un alumno y un profesor al mismo tiempo.']);
        }

        if ($validated['alumno_id'] && $validated['tipo'] != TipoUsuario::ALUMNO) {
            return back()->withErrors(['tipo' => 'Si vincula a un alumno, el tipo debe ser "Alumno".']);
        }

        if ($validated['profesor_id'] && $validated['tipo'] != TipoUsuario::PROFESOR) {
            return back()->withErrors(['tipo' => 'Si vincula a un profesor, el tipo debe ser "Profesor".']);
        }

        // Actualizar datos
        $usuario->update([
            'dni' => $validated['dni'],
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'tipo' => $validated['tipo'],
            'telefono' => $validated['telefono'] ?? null,
            'alumno_id' => $validated['alumno_id'] ?? null,
            'profesor_id' => $validated['profesor_id'] ?? null,
            'pais' => $validated['pais'] ?? $usuario->pais,
            'provincia' => $validated['provincia'] ?? $usuario->provincia,
            'sexo' => $validated['sexo'] ?? $usuario->sexo,
            'activo' => $validated['activo'] ?? true,
        ]);

        // Actualizar contraseña solo si se proporcionó
        if (!empty($validated['password'])) {
            $usuario->update(['clave' => Hash::make($validated['password'])]);
        }

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Alternar estado activo/inactivo
     */
    public function toggle(User $usuario)
    {
        $usuario->update([
            'activo' => !$usuario->activo
        ]);

        $estado = $usuario->activo ? 'activado' : 'desactivado';

        return back()->with('success', "Usuario {$estado} exitosamente.");
    }

    /**
     * Eliminar usuario
     */
    public function destroy(User $usuario)
    {
        // Validar que no se elimine a sí mismo
        if ($usuario->id === auth()->id()) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propio usuario.']);
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Resetear contraseña del usuario
     */
    public function resetPassword(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $usuario->update([
            'clave' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'Contraseña reseteada exitosamente.');
    }

    /**
     * Vista previa de usuarios a generar automáticamente
     */
    public function previewUsuariosAutomaticos()
    {
        // 1. Buscar alumnos sin usuario asignado
        $alumnosSinUsuario = Alumno::whereDoesntHave('user')
            ->get()
            ->map(function ($alumno) {
                $dni_duplicado = User::where('dni', $alumno->dni)->exists();

                return [
                    'id' => $alumno->id,
                    'nombre_completo' => trim($alumno->apellido . ', ' . $alumno->nombre),
                    'dni' => $alumno->dni,
                    'email' => $alumno->email ?: $alumno->dni . '@alumno.ies.edu.ar',
                    'email_generado' => !$alumno->email,
                    'tipo' => 4,
                    'tipo_texto' => 'Alumno',
                    'dni_duplicado' => $dni_duplicado,
                ];
            });

        // 2. Buscar profesores sin usuario asignado
        $profesoresSinUsuario = Profesor::whereDoesntHave('user')
            ->get()
            ->map(function ($profesor) {
                $dni_duplicado = User::where('dni', $profesor->dni)->exists();

                return [
                    'id' => $profesor->id,
                    'nombre_completo' => trim($profesor->apellido . ', ' . $profesor->nombre),
                    'dni' => $profesor->dni,
                    'email' => $profesor->email ?: $profesor->dni . '@profesor.ies.edu.ar',
                    'email_generado' => !$profesor->email,
                    'tipo' => 3,
                    'tipo_texto' => 'Profesor',
                    'dni_duplicado' => $dni_duplicado,
                ];
            });

        return response()->json([
            'alumnos' => $alumnosSinUsuario,
            'profesores' => $profesoresSinUsuario,
            'total' => $alumnosSinUsuario->count() + $profesoresSinUsuario->count(),
            'total_alumnos' => $alumnosSinUsuario->count(),
            'total_profesores' => $profesoresSinUsuario->count(),
        ]);
    }

    /**
     * Generar usuarios automáticamente para alumnos y profesores sin usuario
     */
    public function generarUsuariosAutomaticos(Request $request)
    {
        // Aumentar tiempo de ejecución para operaciones masivas
        set_time_limit(300);

        \Log::info('Iniciando generación automática de usuarios');

        $usuariosCreados = 0;
        $errores = [];

        try {
            // Obtener DNIs existentes para evitar consultas repetidas
            $dnisExistentes = User::pluck('dni')->flip()->toArray();

            // 1. Buscar alumnos sin usuario asignado
            $alumnosSinUsuario = Alumno::whereDoesntHave('user')->get();

            // 2. Buscar profesores sin usuario asignado
            $profesoresSinUsuario = Profesor::whereDoesntHave('user')->get();

            // Preparar datos para inserción masiva
            $usuariosParaCrear = [];
            $now = now();

            // Procesar alumnos
            foreach ($alumnosSinUsuario as $alumno) {
                if (isset($dnisExistentes[$alumno->dni])) {
                    $errores[] = "Alumno {$alumno->nombre} {$alumno->apellido} - DNI {$alumno->dni} ya tiene usuario";
                    continue;
                }

                $usuariosParaCrear[] = [
                    'nombre' => trim($alumno->nombre . ' ' . $alumno->apellido),
                    'usuario' => $alumno->dni,
                    'email' => $alumno->email ?: $alumno->dni . '@alumno.ies.edu.ar',
                    'dni' => $alumno->dni,
                    'clave' => Hash::make($alumno->dni),
                    'tipo' => 4,
                    'activo' => 1,
                    'alumno_id' => $alumno->id,
                    'profesor_id' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // Marcar DNI como usado para evitar duplicados en la misma operación
                $dnisExistentes[$alumno->dni] = true;
            }

            // Procesar profesores
            foreach ($profesoresSinUsuario as $profesor) {
                if (isset($dnisExistentes[$profesor->dni])) {
                    $errores[] = "Profesor {$profesor->nombre} {$profesor->apellido} - DNI {$profesor->dni} ya tiene usuario";
                    continue;
                }

                $usuariosParaCrear[] = [
                    'nombre' => trim($profesor->nombre . ' ' . $profesor->apellido),
                    'usuario' => $profesor->dni,
                    'email' => $profesor->email ?: $profesor->dni . '@profesor.ies.edu.ar',
                    'dni' => $profesor->dni,
                    'clave' => Hash::make($profesor->dni),
                    'tipo' => 3,
                    'activo' => 1,
                    'alumno_id' => null,
                    'profesor_id' => $profesor->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $dnisExistentes[$profesor->dni] = true;
            }

            // Insertar en lotes de 50 para evitar problemas de memoria
            if (count($usuariosParaCrear) > 0) {
                \DB::beginTransaction();

                $chunks = array_chunk($usuariosParaCrear, 50);
                foreach ($chunks as $chunk) {
                    User::insert($chunk);
                    $usuariosCreados += count($chunk);
                }

                \DB::commit();

                \Log::info("Usuarios creados masivamente: {$usuariosCreados}");
            }

            $mensaje = "Se crearon {$usuariosCreados} usuario(s) automáticamente.";
            if (count($errores) > 0) {
                $mensaje .= " Hubo " . count($errores) . " error(es).";
            }

            return back()->with([
                'success' => $mensaje,
                'errores' => $errores
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error en generación automática de usuarios: ' . $e->getMessage());

            return back()->with('error', 'Error al generar usuarios: ' . $e->getMessage());
        }
    }
}
