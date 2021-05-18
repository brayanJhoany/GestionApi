<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Request;

class BitacoraController extends ApiController
{
    /**
     * muestra la bitacora asociados a un usuario
     * @param userId: user identifier.
     * @param cursoId: curso identifier
     */
    public function index($userId, $cursoId)
    {
        $user = User::where('id', $userId)->first(['id']);
        if (is_null($user)) {
            return $this->errorResponse(404, "No se encotro al usuario con identificador {$userId}");
        }
        $curso = Curso::where('id', $cursoId)->first();
        if (is_null($curso)) {
            return $this->errorResponse(404, "No se encotro al curso con identificador {$cursoId}");
        }
        $bitacora = $curso->bitacora;
        return $this->successResponse($bitacora, 200);
    }
}
