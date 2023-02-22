<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void,
     */
    public function up()
    {
        Schema::create('emprendimientos', function (Blueprint $table) {
            //$table->id();
            $table->increments('id');
            $table->string('rol_esfot', 50);
            $table->string('nombre', 255);
            $table->string('descripcion', 255)->nullable();
            $table->string('categoria', 255)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('cobertura', 255)->nullable();
            $table->string('pagina_web', 255)->nullable();
            $table->string('telefono', 10)->nullable();
            $table->string('whatsapp', 10)->nullable();
            $table->string('facebook', 255)->nullable();
            $table->string('instagram', 255)->nullable();
            $table->string('descuento', 255)->nullable();
            $table->boolean('state')->default(true);
            $table->boolean('segundo_estado')->default(false);

            // Relación
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                   ->references('id')
                   ->on('users')
                   ->onDelete('cascade')
                   ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emprendimientos');
    }
};
