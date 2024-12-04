<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = Usuarios::all();

        if (count($usuarios) > 0) {
            return response()->json([
                'message' => 'Se han encontrado estudiantes',
                'data' => $usuarios
            ]);
        } else {
            return response()->json(['message' => 'No se encontraron estudiantes'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:usuarios'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $usuario = Usuarios::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        //     $token = $usuario->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Usuario creado correctamente',
            'usuario:' => $usuario
        ]);
    }


    public function show($id)
    {
        $usuario = Usuarios::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'usuario no encontrado'], 404);
        }

        $data = [
            'message' => 'Estudiante encontrado',
            'data' => $usuario,
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    public function update(Request $request, $id)
    {
        $usuario = Usuarios::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'

        ]);


        if ($validator->fails($request->all())) {
            return response()->json(['message' => 'Error al validar datos de el estudiante'], 422);
        }

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->save();

        $data = [
            'message' => 'Estudiante actualizado con exito',
            'data' => $usuario,
            'status' => 200
        ];
        return response()->json($data, 200);
    }


    public function destroy($id) {

        $usuario = Usuarios::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }

        $usuario->delete();
        $data = [
            'message' => 'Estudiante eliminado',
            'status' => 200
        ];

        return response()->json($data, 200);




    }

    public function login(Request $request)
    {
        $usuario = Usuarios::where('email', $request->email)->first();
        if ($usuario) {
            if (password_verify($request->password, $usuario->password)) {
                $token = $usuario->createToken('authToken')->plainTextToken;
                return response()->json([
                    'message' => 'Login exitoso',
                    'token' => $token
                ]);
            } else {
                return response()->json([
                    'message' => 'Contraseña incorrecta'
                ], 401);
            }
        } else {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }
    }
}
