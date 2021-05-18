<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * displays information about a user
     * @param id: user identifier
     */
    public function show(int $id)
    {
        $user = User::where('id', $id)->first();
        if (is_null($user)) {
            return $this->errorResponse(200, "user not found");
        }
        $data = [
            "error" => false,
            "profesor" => $user,
        ];
        return $this->successResponse($data, 200);
    }
}
