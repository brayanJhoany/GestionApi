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
                'fecha' => '2021-06-05',
                'semana' => 'semana 1',
                'saber_tema' => 'un nuevo proposito',
                'actividad' => 'nueva actividad',
                'observacion' => 'nueva observacion',
                'plan_de_clase_id' => PlanDeClase::all()->random()->id
            ],
            [
                'fecha' => '2021-06-15',
                'semana' => 'semana 2',
                'saber_tema' => 'Nuevo tema',
                'actividad' => 'nueva actividad',
                'observacion' => 'nueva observacion',
                'plan_de_clase_id' => PlanDeClase::all()->random()->id
            ]
        ];
        foreach ($detallePlanDeClases as $detallePlanDeClase) {
            DetallePlanDeClase::updateOrCreate($detallePlanDeClase);
        }
    }
}
