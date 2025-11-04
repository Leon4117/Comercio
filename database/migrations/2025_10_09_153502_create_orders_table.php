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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('event_date');
            $table->decimal('quote_price', 10, 2)->nullable();
            $table->enum('status', ['quoting', 'confirmed', 'completed', 'cancelled'])->default('quoting');
            $table->decimal('quote_price_final', 10, 2)->nullable();
            $table->boolean('urgent')->default(false);
            $table->decimal('urgent_price', 10, 2)->nullable();
            $table->string('chat_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
