<?php

namespace App\Http\Controllers;

use App\Models\Units;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Units::all();

        if (count($units) > 0) {
            return response()->json([
                'message' => 'Se han encontrado unidades',
                'data' => $units
            ]);
        } else {
            return response()->json(['message' => 'No se encontraron unidades'], 404);
        }
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tittle' => 'required',
            'description' => 'required',
            'content' => 'required',
            'course_id' => 'required'
        ]);

        if ($validator->fails($request->all())) {
            return response()->json(['message' => 'Error al validar datos de la unidad '], 422);
        }

        $units = Units::create([
            'tittle' => $request->tittle,
            'description' => $request->description,
            'content' => $request->content,
            'course_id' => $request->course_id

        ]);

        if (!$units) {
            return response()->json(['message' => 'Error al crear el unidad '], 500);
        }

        $data = [
            'message' => 'Inscripcion creado con exito',
            'data' => $units,
            'status' => 201
        ];

        return response()->json($data, 201);
    }


    public function show($id)
    {
        $units = Units::find($id);

        if (!$units) {
            return response()->json(['message' => ' No  se encontraron unidades'], 404);
        }

        $data = [
            'message' => 'Unidad encontrada',
            'data' => $units,
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    public function update(Request $request, $id)
    {
        $units = Units::find($id);
        if (!$units) {
            return response()->json(['message' => 'Unidad  no encontrada'], 404);
        }


        $validator = Validator::make($request->all(), [
            'tittle' => 'required',
            'description' => 'required',
            'content' => 'required',
            'course_id' => 'required'
        ]);


        if ($validator->fails($request->all())) {
            return response()->json(['message' => 'Error al validar datos de la inscripcion'], 422);
        }

        $units->tittle = $request->tittle;
        $units->description = $request->description;
        $units->content = $request->content;
        $units->course_id = $request->course_id;
        $units->save();

        $data = [
            'message' => 'Unidad actualizada con exito',
            'data' => $units,
            'status' => 200
        ];
        return response()->json($data, 200);
    }


    public function destroy($id)
    {
        $units = Units::find($id);
        if (!$units) {
            return response()->json(['message' => 'Unidad no encontrada'], 404);
        }

        $units->delete();
        $data = [
            'message' => 'Unidad eliminada con exito',
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
