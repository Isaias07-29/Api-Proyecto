<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfesorController extends Controller
{

    public function index()
    {
        $profesors = Profesor::all();

        if (count($profesors) > 0) {
            return response()->json([
                'message' => 'Se han encontrado profesores',
                'data' => $profesors
            ]);
        } else {
            return response()->json(['message' => 'No se encontraron profesores'], 404);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'lastName' => 'required',
            'email' => 'required|email|unique:profesors',
            'password' => 'required'
        ]);

        if ($validator->fails($request->all())) {
            return response()->json(['message' => 'Error al validar datos de el profesor'], 422);
        }

        $profesor = Profesor::create([
            'name' => $request->name,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if (!$profesor) {
            return response()->json(['message' => 'Error al crear el profesor '], 500);
        }

        $data = [
            'message' => 'Profesor creado con exito',
            'data' => $profesor,
            'status' => 201
        ];

        return response()->json($data, 201);




    }

    public function show($id)
    {
        $profesor = Profesor::find($id);

        if (!$profesor) {
            return response()->json(['message' => 'Profesor no encontrado'], 404);
        }

        $data = [
            'message' => 'Profesor encontrado',
            'data' => $profesor,
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    public function update(Request $request, $id)
    {
        $profesor = Profesor::find($id);
        if (!$profesor) {
            return response()->json(['message' => 'Profesor no encontrado'], 404);
            }


            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'lastName' => 'required',
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
    
            if ($validator->fails($request->all())) {
                return response()->json(['message' => 'Error al validar datos de el profesor'], 422);
            }
    
            $profesor->name = $request->name;
            $profesor->lastName = $request->lastName;
            $profesor->email = $request->email;
            $profesor->password = bcrypt($request->password);
            $profesor->save();

            $data = [
                'message' => 'Profesor actualizado con exito',
                'data' => $profesor,
                'status' => 200
                ];
                return response()->json($data, 200);
    }


    public function destroy($id)
    {
        $profesor = Profesor::find($id);

        if (!$profesor) {
            return response()->json(['message' => 'Profesor no encontrado'], 404);
        }

        $profesor->delete();
        $data = [
            'message' => 'Profesor eliminado',
            'status' => 200
        ];

        return response()->json($data, 200);
    }
    
}
