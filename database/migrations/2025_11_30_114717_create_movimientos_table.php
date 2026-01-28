<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();

            // Relación con productos
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');

            // Relación opcional con lotes (nullable)
            $table->foreignId('lote_id')->nullable()->constrained('lotes')->onDelete('set null');

            // Relación con usuario que realizó el movimiento
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');

            // Tipo de movimiento y cantidad
            $table->enum('tipo', ['entrada', 'salida']);  // Entrada o salida
            $table->integer('cantidad');                   // Cantidad del movimiento
            $table->string('descripcion')->nullable();     // Motivo del movimiento

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('movimientos');
    }
};
