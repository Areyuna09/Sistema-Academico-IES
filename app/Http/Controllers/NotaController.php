<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\NotaTemporal;
use App\Models\NotaOficial;
use App\Models\HistorialNota;

class NotaController extends Controller
{
    public function listarPorMateria(Request $req)
    {
        $user = auth()->user();
        $profesor = DB::table('tbl_profesores')->where('email', $user->email)->first();
        if (!$profesor) return response()->json([]);

        $materiaId = $req->materia_id;

        $alumnos = DB::table('tbl_materias_alumno')
            ->where('materia', $materiaId)
            ->pluck('alumno')
            ->map(fn($a) => trim((string)$a))
            ->toArray();

        $alumnosData = DB::table('tbl_alumnos')
            ->whereIn(DB::raw('TRIM(CAST(dni AS CHAR))'), $alumnos)
            ->select('id','dni','apellido','nombre')
            ->orderBy('apellido')
            ->get();

        $notas = NotaTemporal::where('materia_id', $materiaId)
            ->where('profesor_id', $profesor->id)
            ->get()
            ->keyBy('alumno_id');

        $result = [];
        foreach ($alumnosData as $a) {
            $tmp = [
                'id' => $a->id,
                'dni' => $a->dni,
                'apellido' => $a->apellido,
                'nombre' => $a->nombre,
                'nota' => null,
                'observacion' => null,
                'estado' => null,
            ];
            if (isset($notas[$a->id])) {
                $nt = $notas[$a->id];
                $tmp['nota'] = $nt->nota;
                $tmp['observacion'] = $nt->observacion;
                $tmp['estado'] = $nt->estado;
                $tmp['nota_temporal_id'] = $nt->id;
            }
            $result[] = $tmp;
        }

        return response()->json($result);
    }

    public function guardarBatch(Request $req)
    {
        $req->validate([
            'materia_id' => 'required|integer',
            'asignaciones' => 'required|array'
        ]);

        $user = auth()->user();
        $profesor = DB::table('tbl_profesores')->where('email', $user->email)->first();
        $fecha = now();

        
        $dniAlumnos = DB::table('tbl_materias_alumno')
            ->where('materia', $req->materia_id)
            ->pluck('alumno')
            ->map(fn($a) => trim((string)$a))
            ->toArray();

        $idsAlumnosMateria = DB::table('tbl_alumnos')
            ->whereIn(DB::raw('TRIM(CAST(dni AS CHAR))'), $dniAlumnos)
            ->pluck('id')
            ->toArray();

        foreach ($req->asignaciones as $a) {
            $alumnoId = (int)$a['alumno_id'];

            
            if (!in_array($alumnoId, $idsAlumnosMateria)) {
                continue; 
            }

            $nota = $a['nota'] ?? null;
            $observacion = $a['observacion'] ?? null;

           
            if ($nota === null && $observacion === null) {
                continue;
            }

            $record = NotaTemporal::updateOrCreate(
                [
                    'alumno_id' => $alumnoId,
                    'materia_id' => $req->materia_id,
                    'profesor_id' => $profesor->id
                ],
                [
                    'nota' => $nota,
                    'observacion' => $observacion,
                    'estado' => 'pendiente',
                    'fecha_carga' => $fecha
                ]
            );

            HistorialNota::create([
                'nota_id' => $record->id,
                'accion' => 'guardada',
                'usuario' => $user->email,
                'fecha' => $fecha
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function aprobar(Request $req, $id)
    {
        $user = auth()->user();
        $profesor = DB::table('tbl_profesores')->where('email', $user->email)->first();

        $nota = NotaTemporal::findOrFail($id);
        if ($nota->profesor_id != $profesor->id) {
            return response()->json(['error'=>'no autorizado'], 403);
        }

        $nota->estado = 'aprobada';
        $nota->save();

        $fecha = Carbon::now()->toDateTimeString();

        HistorialNota::create([
            'nota_id' => $nota->id,
            'accion' => 'aprobada',
            'usuario' => $user->email,
            'fecha' => $fecha
        ]);

        $oficial = NotaOficial::create([
            'alumno_id' => $nota->alumno_id,
            'materia_id' => $nota->materia_id,
            'nota' => $nota->nota,
            'fecha_registro' => $fecha,
            'origen_id' => $nota->id
        ]);

        HistorialNota::create([
            'nota_id' => $nota->id,
            'accion' => 'transferida',
            'usuario' => $user->email,
            'fecha' => $fecha
        ]);

        return response()->json(['status'=>'aprobada','oficial_id'=>$oficial->id]);
    }
}
