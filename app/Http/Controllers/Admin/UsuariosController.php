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
            'dni' => 'required|string|max:20|unique:tbl_usuarios,dni',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tbl_usuarios,email',
            'tipo' => ['required', 'integer', Rule::in([TipoUsuario::ADMIN, TipoUsuario::PROFESOR, TipoUsuario::ALUMNO])],
            'password' => 'required|string|min:6|confirmed',
            'telefono' => 'nullable|string|max:50',
            'alumno_id' => 'nullable|exists:tbl_alumnos,id',
            'profesor_id' => 'nullable|exists:tbl_profesores,id',
            'pais' => 'nullable|exists:tbl_paises,id',
            'provincia' => 'nullable|exists:tbl_provincias,id',
            'sexo' => 'nullable|exists:tbl_sexos,id',
            'activo' => 'boolean',
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
            'dni' => ['required', 'string', 'max:20', Rule::unique('tbl_usuarios')->ignore($usuario->id)],
            'nombre' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('tbl_usuarios')->ignore($usuario->id)],
            'tipo' => ['required', 'integer', Rule::in([TipoUsuario::ADMIN, TipoUsuario::PROFESOR, TipoUsuario::ALUMNO])],
            'password' => 'nullable|string|min:6|confirmed',
            'telefono' => 'nullable|string|max:50',
            'alumno_id' => 'nullable|exists:tbl_alumnos,id',
            'profesor_id' => 'nullable|exists:tbl_profesores,id',
            'pais' => 'nullable|exists:tbl_paises,id',
            'provincia' => 'nullable|exists:tbl_provincias,id',
            'sexo' => 'nullable|exists:tbl_sexos,id',
            'activo' => 'boolean',
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
}
