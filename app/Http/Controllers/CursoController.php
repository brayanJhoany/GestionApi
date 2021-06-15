<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Transformers\SerializerCustom;
use App\Http\Controllers\ApiController;
use App\Transformers\CursoTransformer;
use League\Fractal\Resource\Collection;

class CursoController extends ApiController
{
    /**
     * Entrega un listado de los cursos
     * registrados en la base de datos
     * @param userId: user identifier.
     */
    public function index($userId)
    {
        $cursos = Curso::where('user_id', $userId)->get();
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
