<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Registra un nuevo usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
// Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'nombre_usuario' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'contrasena' => 'required|string|min:8|confirmed', // confirmed buscará contrasena_confirmation
            'rol_usuario' => ['required', 'string', Rule::in(['paciente', 'medico', 'administrador'])],
            'paciente_id' => 'nullable|integer|exists:pacientes,id', // Asegúrate que el paciente exista si se provee
            'medico_id' => 'nullable|integer|exists:medicos,id',   // Asegúrate que el médico exista si se provee
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 400);
        }

        // Crear el nuevo usuario
        $usuario = Usuario::create([
            'nombre_usuario' => $request->nombre_usuario,
            'email' => $request->email,
            'contrasena' => Hash::make($request->contrasena), // Hashear la contraseña
            'rol_usuario' => $request->rol_usuario,
            'paciente_id' => $request->paciente_id,
            'medico_id' => $request->medico_id,
        ]);

        // Generar un token para el nuevo usuario
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'data' => $usuario,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Usuario registrado exitosamente.'
        ], 201);
    }

    /**
     * Inicia sesión para un usuario existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'contrasena' => 'required|string', // Aquí el campo se llama 'contrasena' en el request
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 400);
        }

        // Intentar autenticar al usuario
        // Laravel espera 'password' en las credenciales, así que renombramos 'contrasena' del request.
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->contrasena])) {
            return response()->json([
                'status' => false,
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }

        // Obtener el usuario autenticado
        $usuario = Usuario::where('email', $request->email)->firstOrFail();

        // Generar un token para el usuario
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'data' => $usuario,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Inicio de sesión exitoso.'
        ], 200);
    }

    /**
     * Cierra la sesión del usuario (revoca el token actual).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revocar el token actual del usuario autenticado
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sesión cerrada exitosamente.'
        ], 200);
    }

    /**
     * Obtiene los datos del usuario autenticado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile(Request $request)
    {
        // Retorna el usuario autenticado (cargado por el middleware auth:sanctum)
        return response()->json([
            'status' => true,
            'data' => $request->user(),
            'message' => 'Datos del perfil del usuario obtenidos.'
        ], 200);
    }
}


