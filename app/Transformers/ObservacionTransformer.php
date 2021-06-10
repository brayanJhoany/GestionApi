<?php

namespace App\Transformers;

use App\Models\observacion;
use League\Fractal\TransformerAbstract;

class ObservacionTransformer extends TransformerAbstract
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
    public function transform(Observacion $observacion)
    {
        return [
            "id"            => $observacion->id,
            "titulo"        => $observacion->titulo,
            "descripcion"   => $observacion->descripcion,
            "bitacoraId"    => $observacion->bitacora_id,
            "fechaCreacion" => $observacion->fecha,
        ];
    }
}
