<?php

use App\Http\Controllers\CursoController;
use App\Http\Controllers\DetallePlanDeClaseController;
use App\Http\Controllers\ObservacionController;
use App\Http\Controllers\PlanDeClaseController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\UserController;
use App\Models\DetallePlanDeClase;
use App\Models\Observacion;
use App\Models\PlanDeClases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/profesor/{id}', [UserController::class, 'show']);
Route::group(['prefix' => 'profesor'], function () {
    //usuarios
    Route::get('/{userId}/cursos', [CursoController::class, 'index']);
    Route::get('/{userId}/allcursos', [CursoController::class, 'showAll']);
    Route::get('/{userId}/curso/{cursoId}', [CursoController::class, 'index']);
    //observaciones
    Route::get(
        '{userId}/curso/{cursoId}/bitacora/{bitacoraId}/observaciones',
        [ObservacionController::class, 'index']
    );
    Route::post(
        '{userId}/curso/{cursoId}/bitacora/{bitacoraId}/observacion',
        [ObservacionController::class, 'store']
    );
    Route::put(
        '{userId}/curso/{cursoId}/bitacora/{bitacoraId}/observacion/{id}',
        [ObservacionController::class, 'update']
    );
    Route::get(
        '{userId}/curso/{cursoId}/bitacora/{bitacoraId}/observacion/{id}',
        [ObservacionController::class, 'show']
    );
    Route::delete(
        '{userId}/curso/{cursoId}/bitacora/{bitacoraId}/observacion/{id}',
        [ObservacionController::class, 'destroy']
    );
    //Plan de clases
    Route::get('{usuarioId}/curso/{cursoId}/plan-de-clases', [PlanDeClaseController::class, 'index']);
    Route::get('{usuarioId}/curso/{cursoId}/plan-de-clases/{id}', [PlanDeClaseController::class, 'show']);
    Route::post('{usuarioId}/curso/{cursoId}/plan-de-clases', [PlanDeClaseController::class, 'store']);
    Route::put('{usuarioId}/curso/{cursoId}/plan-de-clases/{id}', [PlanDeClaseController::class, 'update']);
    Route::delete('{usuarioId}/curso/{cursoId}/plan-de-clases/{id}', [PlanDeClaseController::class, 'destroy']);
    //detalle plan de clases
    Route::get(
        '{usuarioId}/curso/{cursoId}/plan-de-clase/{planId}/detalles',
        [DetallePlanDeClaseController::class, 'index']
    );
    Route::get(
        '{usuarioId}/curso/{cursoId}/plan-de-clases/{planId}/detalle/{detalleId}',
        [DetallePlanDeClaseController::class, 'show']
    );
    Route::post(
        '{usuarioId}/curso/{cursoId}/plan-de-clases/{planId}/detalle',
        [DetallePlanDeClaseController::class, 'store']
    );
    Route::put('{usuarioId}/curso/{cursoId}/plan-de-clases/{planId}/detalle/{detalleId}', [DetallePlanDeClaseController::class, 'update']);
    Route::delete('{usuarioId}/curso/{cursoId}/plan-de-clases/{planId}/detalle/{detalleId}', [DetallePlanDeClaseController::class, 'destroy']);
    //syllabus
    Route::get('{usuarioId}/curso/{cursoId}/syllabus', [SyllabusController::class, 'show']);
    Route::post('{usuarioId}/curso/{cursoId}/syllabus', [SyllabusController::class, 'store']);
    Route::put('{usuarioId}/curso/{cursoId}/syllabus', [SyllabusController::class, 'update']);
    Route::delete('{usuarioId}/curso/{cursoId}/syllabus', [SyllabusController::class, 'destroy']);
});
//Syllabus
Route::get('syllabus', [SyllabusController::class, 'index']);
