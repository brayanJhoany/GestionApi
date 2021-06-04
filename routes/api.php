<?php

use App\Http\Controllers\CursoController;
use App\Http\Controllers\ObservacionController;
use App\Http\Controllers\PlanDeClaseController;
use App\Http\Controllers\UserController;
use App\Models\Observacion;
use App\Models\PlanDeClases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
//'middleware' => ['cors']
Route::get('/profesor/{id}', [UserController::class, 'show']);
Route::group(['prefix' => 'profesor'], function () {
    //usuarios
    Route::get('/{userId}/cursos', [CursoController::class, 'index']);
    Route::get('/{userId}/curso/{cursoId}', [CursoController::class, 'index']);
    //observaciones
    Route::get('{userId}/curso/{cursoId}/bitacora/{bitacoraId}/observaciones', [ObservacionController::class, 'index']);
    Route::post('{userId}/curso/{cursoId}/bitacora/{bitacoraId}/observacion', [ObservacionController::class, 'store']);
    Route::put('{userId}/curso/{cursoId}/bitacora/{bitacoraId}/observacion/{id}', [ObservacionController::class, 'update']);
    Route::get('{userId}/curso/{cursoId}/bitacora/{bitacoraId}/observacion/{id}', [ObservacionController::class, 'show']);
    Route::delete('{userId}/curso/{cursoId}/bitacora/{bitacoraId}/observacion/{id}', [ObservacionController::class, 'destroy']);
    //Plan de clases
    Route::get('{usuarioId}/curso/{cursoId}/plan-de-clases', [PlanDeClaseController::class, 'index']);
    Route::get('{usuarioId}/curso/{cursoId}/plan-de-clases/{id}', [PlanDeClaseController::class, 'show']);
    Route::post('{usuarioId}/curso/{cursoId}/plan-de-clases', [PlanDeClaseController::class, 'store']);
    Route::put('{usuarioId}/curso/{cursoId}/plan-de-clases/{id}', [PlanDeClaseController::class, 'update']);
    Route::delete('{usuarioId}/curso/{cursoId}/plan-de-clases/{id}', [PlanDeClaseController::class, 'destroy']);
});
