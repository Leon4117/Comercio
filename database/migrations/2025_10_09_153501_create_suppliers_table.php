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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('location');
            $table->string('price_range'); // Rango de precio
            $table->text('description')->nullable();
            $table->json('documents')->nullable(); // Para papelería (múltiples archivos)
            $table->string('identification_photo')->nullable(); // Foto de identificación oficial
            $table->enum('status', ['pending', 'approved', 'rejected', 'inactive'])->default('pending');
            $table->text('rejection_reason')->nullable(); // Motivo del rechazo
            $table->text('deactivation_reason')->nullable(); // Motivo de la desactivación
            $table->decimal('rating_avg', 3, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
