<?php

namespace Database\Seeders;

use App\Models\DetallePlanDeClases;
use App\Models\Observacion;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(CursoSeeder::class);
        $this->call(BitacoraSeeder::class);
        $this->call(ObservacionSeeder::class);
        $this->call(PlanDeClasesSeeder::class);
        $this->call(DetallePlanDeClaseSeeder::class);
        $this->call(SyllabusSeeder::class);
    }
}
