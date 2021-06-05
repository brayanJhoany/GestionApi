<?php

namespace App\Transformers;

use App\Models\DetallePlanDeClase;
use App\Models\PlanDeClase;
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
        return [
            "id"                    => $detallePlan->id,
            "fecha"                => $detallePlan->fecha,
            "semana"                => $detallePlan->semana,
            "proposito"             => $detallePlan->proposito,
            "actividad"             => $detallePlan->actividad,
            "tiempoPresencial"      => $detallePlan->tiempo_presencial,
            "actividadNoPresencial" => $detallePlan->actividad_no_presencial,
            "trabajoAutonomo"       => $detallePlan->trabajo_autonomo,
            "informacionExtra"      => $detallePlan->informacion_extra,
            "planDeClasesId"        => $detallePlan->plan_de_clase_id,

        ];
    }
}
