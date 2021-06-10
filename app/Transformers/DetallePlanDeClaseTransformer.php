<?php

namespace App\Transformers;

use App\Models\DetallePlanDeClase;
use App\Models\PlanDeClase;
use DateTime;
use League\Fractal\TransformerAbstract;

class DetallePlanDeClaseTransformer extends TransformerAbstract
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
    public function transform(DetallePlanDeClase $detallePlan)
    {
        $fecha = new DateTime($detallePlan->fecha);
        $date = $fecha->format('Y-m-d');

        return [
            "id"                => $detallePlan->id,
            "fecha"             => $date,
            "semana"            => $detallePlan->semana,
            "saber_tema"        => $detallePlan->saber_tema,
            "actividad"         => $detallePlan->actividad,
            "observacion"       => $detallePlan->observacion,
            "planDeClasesId"    => $detallePlan->plan_de_clase_id,

        ];
    }
}
