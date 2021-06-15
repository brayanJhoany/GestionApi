<?php

namespace App\Transformers;

use App\Models\PlanDeClase;
use League\Fractal\TransformerAbstract;

class PlanDeClaseTransformer extends TransformerAbstract
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
    public function transform(PlanDeClase $planDeClase)
    {

        return [
            "id"                    => $planDeClase->id,
            "horarioDeClases"       => json_decode($planDeClase->horario_de_clases),
            "horarioDeConsulta"     => json_decode($planDeClase->horario_de_consulta),
            "cursoId"               => $planDeClase->curso_id
        ];
    }
}
