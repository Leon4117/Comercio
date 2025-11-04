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
        Schema::create('event_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->decimal('quoted_price', 10, 2)->nullable(); // Precio cotizado por el proveedor
            $table->decimal('final_price', 10, 2)->nullable(); // Precio final acordado
            $table->enum('status', ['requested', 'quoted', 'confirmed', 'delivered', 'cancelled'])->default('requested');
            $table->boolean('urgent')->default(false);
            $table->text('notes')->nullable(); // Notas del cliente o proveedor
            $table->string('chat_link')->nullable(); // Enlace al chat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_services');
    }
};
