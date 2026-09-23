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
        Schema::create('vehicle_replacements', function (Blueprint $table) {
            $table->id();

            // Vehicle that was replaced
            $table->foreignId('old_vehicle_id')
                ->constrained('vehicles')
                ->restrictOnDelete();

            // New vehicle that replaced it
            $table->foreignId('new_vehicle_id')
                ->constrained('vehicles')
                ->restrictOnDelete();

            // Admin/user who performed the replacement
            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_replacements');
    }
};