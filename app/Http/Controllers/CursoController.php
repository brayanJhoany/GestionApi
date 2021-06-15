<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Transformers\SerializerCustom;
use App\Http\Controllers\ApiController;
use App\Models\User;
use App\Transformers\CursoTransformer;
use League\Fractal\Resource\Collection;

class CursoController extends ApiController
{
    /**
     * Entrega un listado de los cursos
     * registrados en la base de datos
     * @param usuarioId: user identifier.
     */
    public function index($usuarioId)
    {
        $usuario = User::where('id', $usuarioId)->first();
        if (!$usuario) {
            return $this->errorResponse(404, "No se encontro el usuario con el identificador {$usuarioId}"
                . ", intentelo nuevamente.");
        }
        $cursos = Curso::where('user_id', $usuarioId)->get();
        $manager = new Manager();
        $manager->setSerializer(new SerializerCustom());
        $resource = new Collection($cursos, new CursoTransformer());
        $data = [
            "error" => false,
            "cursos" => SerializerCustom::fractalResponse($resource)
        ];
        return $this->successResponse($data, 200);
    }
}
