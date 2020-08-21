<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('prod_nombre');
            $table->string('prod_descripcion');
            $table->bigInteger('prod_valor');
            $table->unsignedBigInteger('prod_proveedor');
            $table->unsignedBigInteger('prod_cliente');
            
            $table->foreign('prod_proveedor')
                ->references('id')
                ->on('proveedores')
                ->onDelete('cascade');

            $table->foreign('prod_cliente')
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
        Schema::dropIfExists('productos');
    }
}
