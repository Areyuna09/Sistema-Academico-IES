<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\AlumnoCarrera;
use App\Models\Carrera;
use App\Traits\HandlesErrors;
use App\Traits\ChecksPermissions;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlumnoController extends Controller
{
    use HandlesErrors, ChecksPermissions;

    public function create(Request $request)
    {
        $this->autorizarCrear($request);

        $carreras = Carrera::all();

        return Inertia::render('Admin/Alumno/Create', [
            'carreras' => $carreras
        ]);
    }

    public function store(Request $request)
    {
        $this->autorizarCrear($request);

        $validated = $request->validate([
            'dni'            => 'required|string|min:7|max:20|unique:tbl_alumnos,dni|regex:/^[0-9]+$/',
            'apellido'       => 'required|string|min:2|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\'\-]+$/',
            'nombre'         => 'required|string|min:2|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\'\-]+$/',
            'email'          => 'nullable|email|max:255|unique:tbl_alumnos,email',
            'telefono'       => 'nullable|string|max:50|regex:/^[0-9\s\-\+\(\)]*$/',
            'celular'        => 'nullable|string|min:8|max:50|regex:/^[0-9\s\-\+\(\)]*$/',
            'legajo'         => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\-\/]+$/',
            'anno'           => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'carrera'        => 'required|exists:tbl_carreras,Id',
            'curso'          => 'nullable|integer|min:1|max:6',
            'division'       => 'nullable|string|max:10|regex:/^[a-zA-Z0-9]+$/',
            'plan_estudio_id'=> 'nullable|exists:tbl_planes_estudio,id',
            // Segunda carrera (opcional)
            'carrera2'       => 'nullable|exists:tbl_carreras,Id|different:carrera',
            'legajo2'        => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\-\/]+$/',
            'anno2'          => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'curso2'         => 'nullable|integer|min:1|max:6',
            'division2'      => 'nullable|string|max:10|regex:/^[a-zA-Z0-9]+$/',
            'plan_estudio_id2' => 'nullable|exists:tbl_planes_estudio,id',
        ], [
            'dni.required'   => 'El DNI es obligatorio.',
            'dni.unique'     => 'Ya existe un alumno con este DNI.',
            'dni.regex'      => 'El DNI debe contener solo números.',
            'dni.min'        => 'El DNI debe tener al menos 7 dígitos.',
            'apellido.required' => 'El apellido es obligatorio.',
            'nombre.required'   => 'El nombre es obligatorio.',
            'email.unique'   => 'Ya existe un alumno con este email.',
            'carrera.required'  => 'La carrera es obligatoria.',
            'carrera.exists'    => 'La carrera seleccionada no existe.',
            'carrera2.different'=> 'La segunda carrera debe ser diferente a la principal.',
        ]);

        if (empty($validated['anno'])) {
            $validated['anno'] = date('Y');
        }

        $validated['fecha'] = now();

        // Extraer datos de segunda carrera antes de crear el alumno
        $carrera2Data = null;
        if (!empty($validated['carrera2'])) {
            $carrera2Data = [
                'carrera_id'      => $validated['carrera2'],
                'legajo'          => $validated['legajo2'] ?? null,
                'anno'            => $validated['anno2'] ?? null,
                'curso'           => $validated['curso2'] ?? null,
                'division'        => $validated['division2'] ?? null,
                'plan_estudio_id' => $validated['plan_estudio_id2'] ?? null,
            ];
        }

        unset($validated['carrera2'], $validated['legajo2'], $validated['anno2'],
              $validated['curso2'], $validated['division2'], $validated['plan_estudio_id2']);

        try {
            $alumno = Alumno::create($validated);

            if ($carrera2Data) {
                $carreraSecundaria = AlumnoCarrera::create(array_merge(['alumno_id' => $alumno->id], $carrera2Data));
                // Auto-asignar plan si no fue especificado
                if (!$carreraSecundaria->plan_estudio_id) {
                    $plan = $carreraSecundaria->resolverPlan();
                    if ($plan) {
                        $carreraSecundaria->plan_estudio_id = $plan->id;
                        $carreraSecundaria->save();
                    }
                }
            }

            \Log::info('Alumno creado', [
                'alumno_id' => $alumno->id,
                'dni'       => $alumno->dni,
                'creado_por'=> auth()->user()->nombre,
            ]);

            return redirect()
                ->route('expediente.index')
                ->with('success', 'Alumno creado exitosamente');

        } catch (\Exception $e) {
            $this->handleError($e, 'crear alumno', ['dni' => $validated['dni'] ?? null]);
            return back()->withInput()
                ->withErrors(['error' => $this->getFriendlyErrorMessage($e, 'Error al crear el alumno')]);
        }
    }

    public function edit(Request $request, Alumno $alumno)
    {
        $this->autorizarModificar($request);

        $carreras = Carrera::all();

        return Inertia::render('Admin/Alumno/Edit', [
            'alumno'  => $alumno,
            'carreras'=> $carreras
        ]);
    }

    public function update(Request $request, Alumno $alumno)
    {
        $this->autorizarModificar($request);

        $validated = $request->validate([
            'dni'            => 'required|string|max:20|unique:tbl_alumnos,dni,' . $alumno->id . '|regex:/^[0-9]+$/',
            'apellido'       => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'nombre'         => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'email'          => 'nullable|email|max:255',
            'telefono'       => 'nullable|string|max:50|regex:/^[0-9\s\-\+\(\)]*$/',
            'celular'        => 'nullable|string|max:50|regex:/^[0-9\s\-\+\(\)]*$/',
            'legajo'         => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\-\/]+$/',
            'anno'           => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'carrera'        => 'required|exists:tbl_carreras,Id',
            'curso'          => 'nullable|integer|min:1|max:6',
            'division'       => 'nullable|string|max:10|regex:/^[a-zA-Z0-9]+$/',
            'plan_estudio_id'=> 'nullable|exists:tbl_planes_estudio,id',
            // Segunda carrera
            'carrera2'       => 'nullable|exists:tbl_carreras,Id|different:carrera',
            'legajo2'        => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\-\/]+$/',
            'anno2'          => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'curso2'         => 'nullable|integer|min:1|max:6',
            'division2'      => 'nullable|string|max:10|regex:/^[a-zA-Z0-9]+$/',
            'plan_estudio_id2' => 'nullable|exists:tbl_planes_estudio,id',
        ], [
            'dni.required'      => 'El DNI es obligatorio.',
            'dni.unique'        => 'Ya existe otro alumno con este DNI.',
            'apellido.required' => 'El apellido es obligatorio.',
            'nombre.required'   => 'El nombre es obligatorio.',
            'carrera.required'  => 'La carrera es obligatoria.',
            'carrera2.different'=> 'La segunda carrera debe ser diferente a la principal.',
        ]);

        if (empty($validated['anno'])) {
            $validated['anno'] = date('Y');
        }

        if (!$alumno->fecha) {
            $validated['fecha'] = now();
        }

        // Extraer datos de segunda carrera
        $carrera2Id   = $validated['carrera2'] ?? null;
        $carrera2Data = $carrera2Id ? [
            'carrera_id'      => $carrera2Id,
            'legajo'          => $validated['legajo2'] ?? null,
            'anno'            => $validated['anno2'] ?? null,
            'curso'           => $validated['curso2'] ?? null,
            'division'        => $validated['division2'] ?? null,
            'plan_estudio_id' => $validated['plan_estudio_id2'] ?? null,
        ] : null;

        unset($validated['carrera2'], $validated['legajo2'], $validated['anno2'],
              $validated['curso2'], $validated['division2'], $validated['plan_estudio_id2']);

        try {
            $alumno->update($validated);

            // Manejar carrera secundaria
            if ($carrera2Data) {
                $secundaria = AlumnoCarrera::updateOrCreate(
                    ['alumno_id' => $alumno->id, 'carrera_id' => $carrera2Data['carrera_id']],
                    $carrera2Data
                );
                if (!$secundaria->plan_estudio_id) {
                    $plan = $secundaria->resolverPlan();
                    if ($plan) {
                        $secundaria->plan_estudio_id = $plan->id;
                        $secundaria->save();
                    }
                }
                // Eliminar otras carreras secundarias que ya no aplican
                AlumnoCarrera::where('alumno_id', $alumno->id)
                    ->where('carrera_id', '!=', $carrera2Data['carrera_id'])
                    ->delete();
            } else {
                // Se quitó la segunda carrera
                AlumnoCarrera::where('alumno_id', $alumno->id)->delete();
            }

            \Log::info('Alumno actualizado', [
                'alumno_id'    => $alumno->id,
                'dni'          => $alumno->dni,
                'actualizado_por' => auth()->user()->nombre,
            ]);

            return redirect()
                ->route('expediente.index')
                ->with('success', 'Alumno actualizado exitosamente');

        } catch (\Exception $e) {
            $this->handleError($e, 'actualizar alumno', ['alumno_id' => $alumno->id]);
            return back()->withInput()
                ->withErrors(['error' => $this->getFriendlyErrorMessage($e, 'Error al actualizar el alumno')]);
        }
    }

    public function destroy(Request $request, Alumno $alumno)
    {
        $this->autorizarEliminar($request);

        try {
            // Eliminar usuario asociado si existe
            if ($alumno->user) {
                $alumno->user->delete();
            }

            $dni = $alumno->dni;
            $alumno->delete(); // cascade borra tbl_alumno_carreras y carrerasSecundarias

            \Log::info('Alumno eliminado', [
                'dni'          => $dni,
                'eliminado_por'=> auth()->user()->nombre,
            ]);

            return redirect()
                ->route('expediente.index')
                ->with('success', 'Alumno eliminado exitosamente');

        } catch (\Exception $e) {
            $this->handleError($e, 'eliminar alumno', ['alumno_id' => $alumno->id]);
            return back()->withErrors(['error' => $this->getFriendlyErrorMessage($e, 'Error al eliminar el alumno')]);
        }
    }
}
