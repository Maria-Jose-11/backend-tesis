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
            $table->string('descripcion');
            $table->string('categoria', 50)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('cobertura', 100)->nullable();
            $table->string('pagina_web')->nullable();
            $table->string('telefono', 10)->nullable();
            $table->string('whatsapp', 10)->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram', 10)->nullable();
            $table->string('porcentaje', 50)->nullable();
            $table->boolean('state')->default(true);

            // Relación
            $table->unsignedBigInteger('user_id');
            // Un usuario puede realizar muchos reportes y un reporte le pertenece a un usuario            $table->unsignedBigInteger('role_id');
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
