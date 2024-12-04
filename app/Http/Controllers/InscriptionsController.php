<?php

namespace App\Http\Controllers;

use App\Models\Inscriptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class InscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inscriptions = Inscriptions::all();

        if (count($inscriptions) > 0) {
            return response()->json([
                'message' => 'Se han encontrado inscripciones',
                'data' => $inscriptions
            ]);
        } else {
            return response()->json(['message' => 'No se encontraron inscripciones'], 404);
        }
    }

   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'user_id' => 'required',
            'course_id' => 'required'
        ]);

        if ($validator->fails($request->all())) {
            return response()->json(['message' => 'Error al validar datos de el inscripcion'], 422);
        }

        $inscriptions = Inscriptions::create([
            'date' => $request->date,
            'user_id' => $request->user_id,
            'course_id' => $request->course_id
        ]);

        if (!$inscriptions) {
            return response()->json(['message' => 'Error al crear el inscripcion '], 500);
        }

        $data = [
            'message' => 'Inscripcion creado con exito',
            'data' => $inscriptions,
            'status' => 201
        ];

        return response()->json($data, 201);
    }


    public function show($id)
    {
        $inscriptions = Inscriptions::find($id);

        if (!$inscriptions) {
            return response()->json(['message' => 'Curso no inscripciones'], 404);
        }

        $data = [
            'message' => 'Inscripciones encontrado',
            'data' => $inscriptions,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $inscriptions = Inscriptions::find($id);
        if (!$inscriptions) {
            return response()->json(['message' => 'Incripcion no encontrado'], 404);
            }


            $validator = Validator::make($request->all(), [
                'date' => 'required',
                'user_id' => 'required',
                'course_id' => 'required'
            ]);
    
    
            if ($validator->fails($request->all())) {
                return response()->json(['message' => 'Error al validar datos de la inscripcion'], 422);
            }
    
            $inscriptions->date = $request->date;
            $inscriptions->user_id = $request->user_id;
            $inscriptions->course_id = $request->course_id;
            $inscriptions->save();

            $data = [
                'message' => 'Inscripcion actualizado con exito',
                'data' => $inscriptions,
                'status' => 200
                ];
                return response()->json($data, 200);
    }


    public function destroy($id)
    {
        $inscriptions = Inscriptions::find($id);

        if (!$inscriptions) {
            return response()->json(['message' => 'Inscripcion  no encontrada'], 404);
        }

        $inscriptions->delete();
        $data = [
            'message' => 'Inscripcion eliminado',
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    public function getInscriptionsByStudent($id)
    {
        $inscriptions = Inscriptions::where('user_id', $id)->get();

        if ($inscriptions->isEmpty()) {
            return response()->json(['message' => 'El alumno no tiene inscripciones'], 404);
        }

        return response()->json([
            'message' => 'Inscripciones del alumno encontradas',
            'data' => $inscriptions,
            'status' => 200
        ], 200);
    }

    public function getInscriptionsByCourse($id)
    {
        $inscriptions = Inscriptions::where('course_id', $id)->get();

        if ($inscriptions->isEmpty()) {
            return response()->json(['message' => 'El curso no tiene inscripciones'], 404);
        }

        return response()->json([
            'message' => 'Inscripciones del cursp encontradas',
            'data' => $inscriptions,
            'status' => 200
        ], 200);
    }




}
