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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Cliente
            $table->string('name'); // Nombre del evento
            $table->text('description')->nullable(); // Descripción del evento
            $table->date('event_date'); // Fecha del evento
            $table->string('location'); // Ubicación del evento
            $table->decimal('budget', 10, 2)->nullable(); // Presupuesto estimado
            $table->integer('guests_count')->nullable(); // Número de invitados
            $table->enum('status', ['planning', 'confirmed', 'completed', 'cancelled'])->default('planning');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
