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
        Schema::create('afiliados', function (Blueprint $table) {
            $table->id();
            $table->string('ci')->unique();
            $table->string('expedido');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('codigo');
            $table->text('dedo1')->default('');
            $table->text('dedo2')->default('');
            $table->text('dedo3')->default('');
            $table->date('fechaing');
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
        Schema::dropIfExists('afiliados');
    }
};
