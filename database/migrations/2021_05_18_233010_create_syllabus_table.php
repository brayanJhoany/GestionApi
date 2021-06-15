<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyllabusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syllabus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('curso_id')->unsigned();
            $table->integer('nro_creditos');
            $table->string('area_conocimiento');
            $table->integer('semestre');
            $table->json('pre_requisito');
            $table->string('responsable_syllabus');
            $table->json('competencia');
            $table->json('aprendizaje');
            $table->json('unidad');
            $table->string('metodologia');
            $table->json('bibliografia');
            $table->timestamps();
            //foreing key
            $table->foreign('curso_id')->references('id')->on('cursos')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('syllabi');
    }
}
