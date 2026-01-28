<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();

            // Relación con productos
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');

            // Si quieres mantener proveedor, descomenta la siguiente línea
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');

            $table->string('codigo_lote')->nullable();        // opcional
            $table->integer('cantidad_actual')->default(0);    // cantidad disponible en el lote
            $table->date('fecha_ingreso');                    // fecha en la que entra el lote
            $table->date('fecha_vencimiento')->nullable();    // puede ser null si no aplica
            $table->boolean('estado')->default(true);         // activo / inactivo

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
    
};

