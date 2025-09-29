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
        Schema::create('champion', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Campo 'name' para el nombre del producto
            $table->text('description')->nullable(); // Campo 'description' para la descripción del producto, puede ser nulo
            $table->boolean('state')->default(1); //1 = activo, 0 = eliminado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('champion');
    }
};
