<?php

namespace App\Transformers;

use App\Models\Curso;
use League\Fractal\TransformerAbstract;

class CursoTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Curso $curso)
    {
        return [
            "id"        => $curso->id,
            "nombre"    => $curso->nombre,
            "seccion"   => $curso->seccion
        ];
    }
}
