<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    ///UTILIZAR ESTA  TABLA PARA CAMBIAR LA LÓGICA
    public function up()
    {
        Schema::create('imagen_emprendimientos', function (Blueprint $table) {
            $table->id();
            // $table->string('path');
            // //$table->morphs('imageable');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imagen_emprendimientos');
    }
};
