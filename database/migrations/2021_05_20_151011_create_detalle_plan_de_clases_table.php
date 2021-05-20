<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePlanDeClasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_plan_de_clases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('semana', 255);
            $table->text('proposito');
            $table->text('actividad', 255);
            $table->float('tiempo_precencial');
            $table->text('actividad_no_precencial');
            $table->float('trabajo_autonomo');
            $table->json('informacion_extra')->nullable();
            $table->bigInteger('plan_de_clases_id')->unsigned();
            $table->timestamps();
            //foreing key
            $table->foreign('plan_de_clases_id')->references('id')->on('plan_de_clases')
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
        Schema::dropIfExists('detalle_plan_de_clases');
    }
}
