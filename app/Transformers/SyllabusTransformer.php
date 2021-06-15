<?php

namespace App\Transformers;

use App\Models\Syllabus;
use League\Fractal\TransformerAbstract;

class SyllabusTransformer extends TransformerAbstract
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
    public function transform(Syllabus $syllabus)
    {
        return [
            "id"                    => $syllabus->id,
            "nroCreditos"           => $syllabus->nro_creditos,
            "areaconocimiento"      => $syllabus->area_conocimiento,
            "semestre"              => $syllabus->semestre,
            "preRequisito"          => json_decode($syllabus->pre_requisito),
            "responsableSyllabus"   => $syllabus->responsable_syllabus,
            "competencia"           => json_decode($syllabus->competencia),
            "aprendizaje"           => json_decode($syllabus->aprendizaje),
            "unidad"                => json_decode($syllabus->unidad),
            "metodologia"           => $syllabus->metodologia,
            "bibliografia"          => json_decode($syllabus->bibliografia),
        ];
    }
}
