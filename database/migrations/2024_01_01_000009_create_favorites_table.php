<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Favorites — a customer's saved vehicles for one-click rebooking.
 * References the vehicle by registration_no (string FK), consistent with
 * the rest of the schema.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('vehicle_registration_no', 20);
            $table->foreign('vehicle_registration_no')
                ->references('registration_no')
                ->on('vehicles')
                ->cascadeOnDelete();

            $table->timestamps();

            // A user can favorite a given vehicle only once.
            $table->unique(['user_id', 'vehicle_registration_no'], 'favorites_user_vehicle_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
