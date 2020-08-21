<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Clientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('cli_documento')->unique();
            $table->string('cli_nombre');
            $table->string('cli_direccion');
            $table->string('cli_telefono');
            $table->string('cli_email');
            $table->unsignedBigInteger('cli_tipo');
            $table->timestamps();
            
            $table->foreign('cli_tipo')
                ->references('id')
                ->on('tipos')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
