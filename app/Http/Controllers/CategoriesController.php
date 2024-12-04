<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categories::all();

        if (count($categories) > 0) {
            return response()->json([
                'message' => 'Se han encontrado categorias',
                'data' => $categories
            ]);
        } else {
            return response()->json(['message' => 'No se encontraron categorias'], 404);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'

        ]);


        if ($validator->fails($request->all())) {
            return response()->json(['message' => 'Error al validar datos de el Categoria'], 422);
        }

        $categories = Categories::create([
            'name' => $request->name
        ]);

        if (!$categories) {
            return response()->json(['message' => 'Error al crear la categoria'], 500);
        }

        $data = [
            'message' => 'Categoria creado con exito',
            'data' => $categories,
            'status' => 201
        ];

        return response()->json($data, 201);
    }


    public function show($id)
    {
        $categories = Categories::find($id);

        if (!$categories) {
            return response()->json(['message' => 'Categoria no encontrado'], 404);
        }

        $data = [
            'message' => 'Categoria encontrado',
            'data' => $categories,
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    public function update(Request $request, $id)
    {
        $categories = Categories::find($id);
        if (!$categories) {
            return response()->json(['message' => 'Categoria no encontrado'], 404);
            }


            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);
    
    
            if ($validator->fails($request->all())) {
                return response()->json(['message' => 'Error al validar datos de el profesor'], 422);
            }
    
            $categories->name = $request->name;
            $categories->save();

            $data = [
                'message' => 'Categoria actualizada con exito',
                'data' => $categories,
                'status' => 200
                ];
                return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $categories = Categories::find($id);

        if (!$categories) {
            return response()->json(['message' => 'Categoria no encontrada'], 404);
        }

        $categories->delete();
        $data = [
            'message' => 'Categoria eliminado',
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
