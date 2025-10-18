<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asistencia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    
    public function materiasDelProfesor(Request $req)
    {
        $user = auth()->user();
        $profesor = DB::table('tbl_profesores')->where('email', $user->email)->first();

        if (!$profesor) {
            return response()->json([], 200);
        }

        $materias = DB::table('tbl_profesor_tiene_materias')
            ->join('tbl_materias', 'tbl_materias.id', '=', 'tbl_profesor_tiene_materias.materia')
            ->where('profesor', $profesor->id)
            ->select('tbl_materias.id', 'tbl_materias.nombre')
            ->get();

        return response()->json($materias);
    }

    
    public function alumnosPorMateria($materiaId)
    {
        $inscriptos = DB::table('tbl_materias_alumno')
            ->where('materia', $materiaId)
            ->pluck('alumno');

        $alumnos = DB::table('tbl_alumnos')
            ->whereIn('dni', $inscriptos)
            ->select('id', 'apellido', 'nombre', 'dni')
            ->orderBy('apellido')
            ->get();

        return response()->json($alumnos);
    }

    
    public function listarPorMateriaFecha(Request $req)
    {
        $fecha = Carbon::parse($req->fecha)->format('Y-m-d');
        $asistencias = Asistencia::where('materia_id', $req->materia_id)
            ->where('fecha', $fecha)
            ->get();

        return response()->json($asistencias);
    }

    
    public function guardarBatch(Request $req)
    {
        $profesor = DB::table('tbl_profesores')->where('email', auth()->user()->email)->first();
        $fecha = Carbon::parse($req->fecha)->format('Y-m-d');

        foreach ($req->asistencias as $a) {
            Asistencia::updateOrCreate(
                [
                    'alumno_id' => $a['alumno_id'],
                    'materia_id' => $req->materia_id,
                    'fecha' => $fecha,
                ],
                [
                    'profesor_id' => $profesor->id,
                    'estado' => $a['estado'],
                    'observacion' => $a['observacion'] ?? null,
                ]
            );
        }

        return response()->json(['status' => 'ok']);
    }

   
    public function destroy($id)
    {
        Asistencia::findOrFail($id)->delete();
        return response()->json(['status' => 'deleted']);
    }
}
