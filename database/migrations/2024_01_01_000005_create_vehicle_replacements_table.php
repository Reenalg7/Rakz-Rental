<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Vehicle Replacements table
 * ---------------------------------------------------------------------------
 * Full audit trail: each row records one admin swap of a vehicle on a booking.
 * Both "from" and "to" vehicles are referenced by registration_no (string FK).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_replacements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->cascadeOnDelete();

            // FK -> vehicles.registration_no (the vehicle being replaced).
            $table->string('original_registration_no', 20);
            $table->foreign('original_registration_no')
                ->references('registration_no')
                ->on('vehicles')
                ->restrictOnDelete();

            // FK -> vehicles.registration_no (the new vehicle).
            $table->string('replacement_registration_no', 20);
            $table->foreign('replacement_registration_no')
                ->references('registration_no')
                ->on('vehicles')
                ->restrictOnDelete();

            $table->text('reason')->nullable();

            $table->foreignId('replaced_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_replacements');
    }
};
