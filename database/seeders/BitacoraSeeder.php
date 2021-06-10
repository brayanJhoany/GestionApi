<?php

namespace Database\Seeders;

use App\Models\Bitacora;
use App\Models\Curso;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BitacoraSeeder extends Seeder
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
            $request = [
                'curso_id' => $curso->id,
            ];
            Bitacora::updateOrCreate($request);
        }
    }
}
