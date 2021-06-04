<?php

namespace Database\Seeders;

use App\Models\PlanDeClase;
use Illuminate\Database\Seeder;
use App\Models\DetallePlanDeClase;

class DetallePlanDeClaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $detallePlanDeClases = [
            [
                'semana' => 'semana 1',
                'proposito' => 'un nuevo proposito',
                'actividad' => 'nueva actividad',
                'tiempo_precencial' => 2.5,
                'actividad_no_precencial' => 'estudia mucho',
                'trabajo_autonomo' => 2.5,
                'informacion_extra' => null,
                'plan_de_clases_id' => PlanDeClase::all()->random()->id
            ],
            [
                'semana' => 'semana 2',
                'proposito' => 'un nuevo proposito',
                'actividad' => 'nueva actividad',
                'tiempo_precencial' => 2.5,
                'actividad_no_precencial' => 'estudia mucho',
                'trabajo_autonomo' => 2.5,
                'informacion_extra' => null,
                'plan_de_clases_id' => PlanDeClase::all()->random()->id
            ]
        ];
        foreach ($detallePlanDeClases as $detallePlanDeClase) {
            DetallePlanDeClase::updateOrCreate($detallePlanDeClase);
        }
    }
}
