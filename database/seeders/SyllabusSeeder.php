<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\Syllabus;
use Illuminate\Database\Seeder;

class SyllabusSeeder extends Seeder
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
            $syllabus = [
                "curso_id" => $curso->id,
                "nro_creditos" => 5,
                "area_conocimiento" => "semana 1",
                "semestre" => 1,
                "pre_requisito" => json_encode(["cursoIds" => [Curso::all()->random()->id]]),
                "responsable_syllabus" => "Brayan Escobar",
                "competencia" => json_encode(["competencia 1", "competencia 2"]),
                "aprendizaje" => json_encode(["aprendizaje 1", "aprendizaje 2"]),
                "unidad" => json_encode(["unidad 1", "unidad 2"]),
                "metodologia" => "dos laboratorios practicos",
                "bibliografia" => json_encode(["bibliografia 1", "bibliografia 2"])
            ];
            Syllabus::updateOrCreate($syllabus);
        }
    }
}
