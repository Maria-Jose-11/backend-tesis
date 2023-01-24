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
        Schema::table('emprendimientos', function (Blueprint $table) {
            // $table->unsignedBigInteger('imagen_id')->after('id')->nullable();
            
            // $table->foreign('imagen_id')
            //         ->references('id')
            //         ->on('images')
            //         ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emprendimientos', function (Blueprint $table) {
            //$table->dropForeign(['imagen_id']);
        });
    }
};
