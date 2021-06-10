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
        $bitacoras = Bitacora::all();
        foreach ($bitacoras as $bitacora) {
            $request = [
                'titulo' => 'Titulo 1',
                'descripcion' => 'descripcion 1',
                'bitacora_id' => $bitacora->id
            ];
            Observacion::updateOrCreate($request);
        }
    }
}
