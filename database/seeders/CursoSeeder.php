<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Database\Seeder;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cursos = [
            [
                'nombre' => 'Introducción a la programación',
                'user_id' => User::all()->random()->id
            ],
            [
                'nombre' => 'Introducción a la programación',
                'user_id' => User::all()->random()->id
            ],
            [
                'nombre' => 'Teoría de sistemas',
                'user_id' => User::all()->random()->id
            ],
            // [
            //     'nombre' => 'Introducción a la ICC',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Interfaz H-M',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Pensamiento Computacional',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Programación avanzada',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Lenguajes y paradigmas de programación',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Proyecto de Programación',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Modelos Discretos',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Algoritmos y Estructuras de Datos',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Metodologías y Planificación',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Diseño de Bases de Datos',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Diseño de software',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Arquitectura de computadores y DCD',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Construcción y Validación',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Redes de Computadores',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Sistemas Operativos',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Gestión de Bases de Datos',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Administración de Redes....',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Taller de Desarrollo de Software',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Gestión de Proyectos Tecnológicos',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Seguridad Informatica',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Formulación de Proyecto de Titulación',
            //     'user_id' => User::all()->random()->id
            // ],
            // [
            //     'nombre' => 'Proyecto de Titulación',
            //     'user_id' => User::all()->random()->id
            // ],

        ];

        foreach ($cursos as $curso) {
            Curso::updateOrCreate($curso);
        }
    }
}
