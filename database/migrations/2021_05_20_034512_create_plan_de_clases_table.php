<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanDeClasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_de_clases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('horario_de_clases');
            $table->json('horario_de_consulta');
            $table->bigInteger('curso_id')->unsigned();
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
        Schema::dropIfExists('plan_de_clases');
    }
}
