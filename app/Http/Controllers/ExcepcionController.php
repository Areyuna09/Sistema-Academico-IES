<?php

namespace App\Http\Controllers;

use App\Models\Excepciones;
use Illuminate\Http\Request;
use App\Models\Alumno;
use Inertia\Inertia;

class ExcepcionController extends Controller
{


public function index(Request $request)
{
    $search = $request->input('search');

    $excepciones = Excepciones::with('alumno')
        ->when($search, function ($query, $search) {
            return $query->whereHas('alumno', function ($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%')
                  ->orWhere('apellido', 'like', '%' . $search . '%');
            });
        })
        ->orderBy('fecha_envio', 'asc')
        ->get();

    // 👇 Acá agregamos la lista de alumnos
    $alumnos = Alumno::orderBy('apellido')->get();

    return Inertia::render('Excepciones/Excepciones', [
        'excepciones' => $excepciones,
        'alumnos'     => $alumnos, // 👈 ahora tu Vue recibe alumnos
    ]);
}


  public function store(Request $request)
{
    $request->validate([
        'alumno' => 'required|exists:tbl_alumnos,id',
        'tipo_excepcion' => 'required|string|in:Inasistencia,Justificacion,Reingreso,Cambio de Carrera,Cambio de Plan',
    ]);

    Excepciones::create([
        'alumno'        => $request->alumno,
        'tipo_excepcion'=> $request->tipo_excepcion,
        'fecha_envio'   => now(), // fecha automática
        'estado'        => 'pendiente',
        'justificacion' => null,
    ]);

    return redirect()->route('excepciones')
                     ->with('success', 'Excepción creada correctamente.');
}


  public function update(Request $request, Excepciones $excepcion)
  {
    $request->validate([
      'estado' => 'required|in:aprobada,rechazada',
      'justificacion' => 'nullable|string',
    ]);

    $excepcion->update($request->only('estado', 'justificacion'));

    return redirect()->route('excepciones')->with('success', 'Excepción actualizada correctamente.');
  } 

  public function destroy($id)
{
    $excepcion = Excepciones::findOrFail($id);
    $excepcion->delete();

    return redirect()->back()->with('success', 'Excepción eliminada correctamente');
}


}