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
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('detalle');
            $table->double('monto');
            $table->double('multa');
            $table->string('rango');
            $table->double('sindical',11,2);
            $table->double('seguro',11,2);
            $table->double('deportico',11,2);
            $table->double('decano',11,2);
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
        Schema::dropIfExists('grupos');
    }
};
