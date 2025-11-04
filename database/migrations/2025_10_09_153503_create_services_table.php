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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nombre del servicio/paquete
            $table->decimal('base_price', 10, 2); // Precio base
            $table->text('description'); // Descripción detallada
            $table->string('main_image')->nullable(); // Imagen principal
            $table->json('portfolio_images')->nullable(); // Portafolio de imágenes
            $table->boolean('urgent_available')->default(false); // Si acepta urgentes
            $table->decimal('urgent_price_extra', 10, 2)->nullable(); // Costo extra por urgente
            $table->boolean('active')->default(true); // Si está activo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
