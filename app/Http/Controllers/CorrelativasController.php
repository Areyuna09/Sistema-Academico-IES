<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Materias;
use App\Models\Correlativas;
use Illuminate\Http\Request;

class CorrelativasController extends Controller
{
    public function index()
    {
        return view('correlativas.index');
    }

    public function seleccionar(Request $request)
    {
        if (!$request->carrera_id) {
            return back()->with('error', 'Por favor seleccione una carrera');
        }

        $carrerasPermitidas = [1, 2, 3, 4, 5];
        if (!in_array($request->carrera_id, $carrerasPermitidas)) {
            return back()->with('error', 'Carrera no válida');
        }

        return redirect()->route('correlativas.gestionar', ['carreraId' => $request->carrera_id]);
    }

    public function gestionar($carreraId)
    {
        $nombresCarreras = [
            1 => 'TSDS',
            2 => 'PEI', 
            3 => 'PEP',
            4 => 'TST',
            5 => 'TSM'
        ];

        if (!array_key_exists($carreraId, $nombresCarreras)) {
            abort(404, 'Carrera no encontrada');
        }

        $carrera = (object)[
            'id' => $carreraId,
            'nombre' => $nombresCarreras[$carreraId]
        ];

        $materias = Materias::where('carrera', $carreraId)
            ->orderBy('nombre')
            ->get();

        $correlativasConfiguradas = [];
        foreach ($materias as $materia) {
            $correlativas = Correlativas::where('materia', $materia->id)
                ->join('tbl_materias', 'correlativas.correlativa', '=', 'tbl_materias.id')
                ->select('tbl_materias.id', 'tbl_materias.nombre')
                ->get();

            if ($correlativas->count() > 0) {
                $correlativasConfiguradas[$materia->id] = [
                    'materia' => $materia,
                    'correlativas' => $correlativas
                ];
            }
        }

        return view('correlativas.gestionar', compact('carrera', 'materias', 'correlativasConfiguradas'));
    }

    public function getCorrelativas($materiaId)
    {
        $correlativas = Correlativas::where('materia', $materiaId)
            ->pluck('correlativa')
            ->toArray();

        return response()->json($correlativas);
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'materia_id' => 'required|exists:tbl_materias,id',
            'correlativas' => 'array'
        ]);

        $materiaId = $request->materia_id;
        $correlativas = $request->correlativas ?? [];

        Correlativas::where('materia', $materiaId)->delete();

        foreach ($correlativas as $correlativaId) {
            if ($correlativaId != $materiaId) {
                Correlativas::create([
                    'materia' => $materiaId,
                    'correlativa' => $correlativaId
                ]);
            }
        }

        $materia = Materias::find($materiaId);
        $carreraId = $materia->carrera;

        return redirect()
            ->route('correlativas.gestionar', ['carreraId' => $carreraId])
            ->with('success', "Correlativas guardadas correctamente para {$materia->nombre}");
    }

    public function eliminar($materiaId)
    {
        $materia = Materias::findOrFail($materiaId);
        $carreraId = $materia->carrera;

        Correlativas::where('materia', $materiaId)->delete();

        return redirect()
            ->route('correlativas.gestionar', ['carreraId' => $carreraId])
            ->with('success', "Se eliminaron todas las correlativas de '{$materia->nombre}'");
    }

}