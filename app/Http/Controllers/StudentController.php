<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();

        if (count($students) > 0) {
            return response()->json([
                'message' => 'Se han encontrado estudiantes',
                'data' => $students
            ]);
        } else {
            return response()->json(['message' => 'No se encontraron estudiantes'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'semester' => 'required'

        ]);


        if ($validator->fails($request->all())) {
            return response()->json(['message' => 'Error al validar datos de el estudiante'], 422);
        }

        $student = Student::create([
            'name' => $request->name,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'semester' => $request->semester
            
        ]);

        if (!$student) {
            return response()->json(['message' => 'Error al crear el estudiante 1'], 500);
        }

        $data = [
            'message' => 'Estudiante creado con exito',
            'data' => $student,
            'status' => 201
        ];

        return response()->json($data, 201);
    }


    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }

        $data = [
            'message' => 'Estudiante encontrado',
            'data' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
            }


            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'lastName' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'semester' => 'required'
    
            ]);
    
    
            if ($validator->fails($request->all())) {
                return response()->json(['message' => 'Error al validar datos de el estudiante'], 422);
            }
    
            $student->name = $request->name;
            $student->lastName = $request->lastName;
            $student->email = $request->email;
            $student->password = bcrypt($request->password);
            $student->semester = $request->semester;
            $student->save();

            $data = [
                'message' => 'Estudiante actualizado con exito',
                'data' => $student,
                'status' => 200
                ];
                return response()->json($data, 200);
    }


    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }

        $student->delete();
        $data = [
            'message' => 'Estudiante eliminado',
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
