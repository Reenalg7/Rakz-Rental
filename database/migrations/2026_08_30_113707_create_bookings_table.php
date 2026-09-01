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
        Schema::create('bookings', function (Blueprint $table) {
    $table->id();

    $table->foreignId('vehicle_id')
          ->constrained('vehicles')
          ->onDelete('cascade');

    $table->string('customer_name');

    $table->date('pickup_date');
    $table->date('return_date');

    $table->decimal('total_amount', 10, 2);

    $table->enum('status', [
        'pending',
        'confirmed',
        'cancelled'
    ])->default('pending');

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
