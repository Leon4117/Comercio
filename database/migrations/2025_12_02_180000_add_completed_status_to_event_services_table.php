<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE event_services MODIFY COLUMN status ENUM('requested', 'quoted', 'confirmed', 'delivered', 'cancelled', 'completed') NOT NULL DEFAULT 'requested'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Warning: This might fail if there are records with 'completed' status
        DB::statement("ALTER TABLE event_services MODIFY COLUMN status ENUM('requested', 'quoted', 'confirmed', 'delivered', 'cancelled') NOT NULL DEFAULT 'requested'");
    }
};
