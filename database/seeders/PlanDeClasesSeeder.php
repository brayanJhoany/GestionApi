<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\PlanDeClase;
use Illuminate\Database\Seeder;

class PlanDeClasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $PlanDeClases = [
            [
                'horario_de_clases' => json_encode([
                    'lunes' => "10:30-11:30"
                ]),
                'horario_de_consulta' => json_encode([
                    'viernes' => "10:30-11:30"
                ]),
                'curso_id' => Curso::all()->random()->id
            ],
            [
                'horario_de_clases' => json_encode([
                    'lunes' => "10:30-11:30"
                ]),
                'horario_de_consulta' => json_encode([
                    'viernes' => "10:30-11:30"
                ]),
                'curso_id' => Curso::all()->random()->id
            ],
        ];
        foreach ($PlanDeClases as $PlanDeClase) {
            PlanDeClase::updateOrCreate($PlanDeClase);
        }
    }
}
