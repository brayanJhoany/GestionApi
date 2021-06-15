<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * muestra la informacion de un usuario en especifico.
     * @param id: identificador de un usuario.
     * @return User
     */
    public function show(int $id)
    {
        $user = User::where('id', $id)->first();
        if (is_null($user)) {
            return $this->errorResponse(200, "No se encotro el usuario");
        }
        $data = [
            "error" => false,
            "profesor" => $user,
        ];
        return $this->successResponse($data, 200);
    }
}
