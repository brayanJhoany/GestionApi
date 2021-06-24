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
        $cursos = Curso::all();
        foreach ($cursos as $curso) {
            $PlanDeClase = [

                'horario_de_clases' => json_encode([
                    'horarios' => array()
                ]),
                'horario_de_consulta' => json_encode([
                    'horarios' => array()
                ]),
                'curso_id' => $curso->id

            ];
            PlanDeClase::updateOrCreate($PlanDeClase);
        }
    }
}
