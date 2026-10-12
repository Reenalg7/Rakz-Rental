<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bookings table
 * ---------------------------------------------------------------------------
 * Uses `vehicle_registration_no` (string FK referencing vehicles.registration_no)
 * instead of a numeric vehicle_id, per the lecturer's requirement.
 *
 * `original_vehicle_registration_no` preserves booking history when an admin
 * swaps the vehicle via a replacement.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // FK -> vehicles.registration_no (string). Current vehicle on this booking.
            $table->string('vehicle_registration_no', 20);
            $table->foreign('vehicle_registration_no')
                ->references('registration_no')
                ->on('vehicles')
                ->restrictOnDelete();

            // Preserves the original vehicle (set when a replacement happens).
            $table->string('original_vehicle_registration_no', 20)->nullable();
            $table->foreign('original_vehicle_registration_no')
                ->references('registration_no')
                ->on('vehicles')
                ->nullOnDelete();

            $table->date('pickup_date');
            $table->date('return_date');
            $table->unsignedSmallInteger('total_days');
            $table->decimal('daily_rate_snapshot', 10, 2);
            $table->decimal('total_amount', 12, 2);

            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])
                ->default('pending')
                ->index();

            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes help the availability-overlap query stay fast.
            $table->index(['vehicle_registration_no', 'pickup_date', 'return_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
