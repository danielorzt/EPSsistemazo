<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importa tus controladores de API
use App\Http\Controllers\API\UsuarioController;
use App\Http\Controllers\API\EspecialidadController;
use App\Http\Controllers\API\MedicoController;
use App\Http\Controllers\API\PacienteController;
use App\Http\Controllers\API\CitaController;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas de Autenticación públicas
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Rutas de autenticación protegidas
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user-profile', [AuthController::class, 'userProfile']);

    // Rutas solo para Administradores
    Route::middleware('role:administrador')->group(function () {
        Route::apiResource('usuarios', UsuarioController::class);
        Route::apiResource('especialidades', EspecialidadController::class)->except(['index', 'show']);
        Route::post('medicos', [MedicoController::class, 'store']);
        Route::put('medicos/{medico}', [MedicoController::class, 'update']);
        Route::delete('medicos/{medico}', [MedicoController::class, 'destroy']);
        Route::apiResource('especialidades', EspecialidadController::class)->except(['index', 'show']);
        // Agrega aquí otras rutas de administración si lo necesitas
    });

    // Rutas para Administradores y Médicos
    Route::middleware('role:administrador,medico')->group(function () {
        Route::get('medicos', [MedicoController::class, 'index']);
        Route::get('medicos/{medico}', [MedicoController::class, 'show']);
        Route::get('citas', [CitaController::class, 'index']);
        Route::get('citas/medico/{id_medico}/rango-fechas', [CitaController::class, 'citasPorMedicoYFechas']);
        Route::put('citas/{cita}', [CitaController::class, 'update']);
    });

    // Rutas para Pacientes, Administradores y Médicos
    Route::middleware('role:paciente,administrador,medico')->group(function () {
        Route::get('pacientes/{paciente}', [PacienteController::class, 'show']);
        Route::get('medicos/por-especialidad/{id_especialidad}', [MedicoController::class, 'porEspecialidad']);
    });

    // Rutas para Pacientes y Administradores
    Route::middleware('role:paciente,administrador')->group(function () {
        Route::put('pacientes/{paciente}', [PacienteController::class, 'update']);
        Route::post('citas', [CitaController::class, 'store']);
        Route::delete('citas/{cita}', [CitaController::class, 'destroy']);
    });

    // Rutas comunes (disponibles para cualquier rol autenticado)
    Route::get('especialidades', [EspecialidadController::class, 'index']);
    Route::get('especialidades/{especialidad}', [EspecialidadController::class, 'show']);
    Route::get('citas/paciente/{id_paciente}/proximas', [CitaController::class, 'proximasCitasPorPaciente']);
    Route::get('citas/{cita}', [CitaController::class, 'show']);
});

// Rutas públicas (sin autenticación)
Route::get('pacientes/top-citas', [PacienteController::class, 'pacientesConMasCitas']);
Route::get('especialidades/conteo-citas', [EspecialidadController::class, 'conteoCitasPorEspecialidad']);
