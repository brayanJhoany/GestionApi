<?php

namespace Database\Seeders;

use App\Models\Bitacora;
use App\Models\Observacion;
use Illuminate\Database\Seeder;

class ObservacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $observaciones = [
            [
                'titulo' => 'Titulo 1',
                'descripcion' => 'descripcion 1',
                'bitacora_id' => Bitacora::all()->random()->id

            ],
            [
                'titulo' => 'Titulo 2',
                'descripcion' => 'descripcion 2',
                'bitacora_id' => Bitacora::all()->random()->id

            ],
            [
                'titulo' => 'Titulo 3',
                'descripcion' => 'descripcion 4',
                'bitacora_id' => Bitacora::all()->random()->id

            ],
        ];

        foreach ($observaciones as $observacion) {
            Observacion::updateOrCreate($observacion);
        }
    }
}
