<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Empleados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('emp_documento')->unique();
            $table->string('emp_nombre');
            $table->string('emp_direccion');
            $table->string('emp_telefono');
            $table->string('emp_email');
            $table->unsignedBigInteger('emp_cliente');
            $table->timestamps();
            
            $table->foreign('emp_cliente')
                ->references('id')
                ->on('clientes')
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
        Schema::dropIfExists('empleados');
    }
}
