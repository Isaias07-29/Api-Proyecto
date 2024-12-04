<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Courses::all();

        if (count($courses) > 0) {
            return response()->json([
                'message' => 'Se han encontrado cursos',
                'data' => $courses
            ]);
        } else {
            return response()->json(['message' => 'No se encontraron cursos'], 404);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'profesor_id' => 'required'
        ]);

        if ($validator->fails($request->all())) {
            return response()->json(['message' => 'Error al validar datos de el curso'], 422);
        }

        $courses = Courses::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'profesor_id' => $request->profesor_id
        ]);

        if (!$courses) {
            return response()->json(['message' => 'Error al crear el Curso '], 500);
        }

        $data = [
            'message' => 'Curso creado con exito',
            'data' => $courses,
            'status' => 201
        ];

        return response()->json($data, 201);
    }


    public function show($id)
    {
        $courses = Courses::find($id);

        if (!$courses) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }

        $data = [
            'message' => 'Curso encontrado',
            'data' => $courses,
            'status' => 200
        ];

        return response()->json($data, 200);
    }



    public function update(Request $request, $id)
    {
        $courses = Courses::find($id);
        if (!$courses) {
            return response()->json(['message' => 'Profesor no encontrado'], 404);
        }


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'profesor_id' => 'required'
        ]);


        if ($validator->fails($request->all())) {
            return response()->json(['message' => 'Error al validar datos de el curso'], 422);
        }

        $courses->name = $request->name;
        $courses->description = $request->description;
        $courses->category_id = $request->category_id;
        $courses->profesor_id = $request->profesor_id;
        $courses->save();

        $data = [
            'message' => 'Curso actualizado con exito',
            'data' => $courses,
            'status' => 200
        ];
        return response()->json($data, 200);
    }


    public function destroy($id)
    {
        $courses = Courses::find($id);

        if (!$courses) {
            return response()->json(['message' => 'Curso no encontrado'], 404);
        }

        $courses->delete();
        $data = [
            'message' => 'Curso eliminado',
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    public function getCoursesByProfessor($profesor_id)
    {
        $courses = Courses::where('profesor_id', $profesor_id)->get();

        if ($courses->isEmpty()) {
            return response()->json(['message' => 'El profesor no tiene cursos asignados'], 404);
        }

        return response()->json([
            'message' => 'Cursos del profesor encontrados',
            'data' => $courses,
            'status' => 200
        ], 200);
    }

    public function getCoursesByCategory($category_id)
    {
        $courses = Courses::where('category_id', $category_id)->get();

        if ($courses->isEmpty()) {
            return response()->json(['message' => 'No hay cursos con la categoria '], 404);
        }

        return response()->json([
            'message' => 'Cursos de la categoria encontrados',
            'data' => $courses,
            'status' => 200
        ], 200);
    }
}
