<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    /**
     * shows the courses associated with a user
     * @param userId: user identifier.
     * @param cursoId: curso identifier
     */
    public function index($userId, $cursoId)
    {
        $user = User::where('id', $userId)->first(['id']);
        if (is_null($user)) {
            //error response
        }
        $curso = Curso::where('id', $cursoId)->first();
        if (is_null($curso)) {
            //error response
        }
        $bitacora = $curso->bitacora;
        $observaciones = $bitacora->observaciones;
        dd($curso);
    }
}
