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
    public function up()
    {
        Schema::create('emprendimientos', function (Blueprint $table) {
            $table->id();
            $table->string('rol_esfot', 50);
            $table->string('nombre',50);
            $table->string('descripcion', 50)->nullable();
            $table->string('categoria', 50)->nullable();
            $table->string('direccion')->nullable();
            $table->string('cobertura')->nullable();
            $table->string('pagina_web')->nullable();
            $table->string('telefono', 10)->nullable();
            $table->string('whatsapp', 10)->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('porcentaje')->nullable();
            $table->boolean('state')->default(true);

            // Relación
            $table->unsignedBigInteger('user_id');
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
