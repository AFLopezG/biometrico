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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('afiliado_id');
            $table->foreign('afiliado_id')->references('id')->on('afiliados');
            $table->unsignedBigInteger('grupo_id');
            $table->foreign('grupo_id')->references('id')->on('grupos');
            $table->unsignedBigInteger('vehiculo_id');
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->double('monto',11,2)->nullable();;
            $table->boolean('multa')->default(false)->nullable();;
            $table->double('sindical',11,2);
            $table->double('seguro',11,2);
            $table->double('deportico',11,2);
            $table->double('decano',11,2);
            $table->date('fecha')->nullable();;
            $table->time('hora')->nullable();;
            $table->boolean('impreso')->default(false);
            $table->boolean('anulado')->default(false);
            $table->string('motivo')->nullable();
            $table->integer('color')->nullable()->default(0);
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
        Schema::dropIfExists('pagos');
    }
};
