<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Vehicles table
 * ---------------------------------------------------------------------------
 * The primary key is `registration_no` (string), not
 * an auto-incrementing numeric id. All downstream tables reference this
 * registration number as their foreign key.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            // PRIMARY KEY is the vehicle registration number (e.g. "LR3180").
            $table->string('registration_no', 20)->primary();

            $table->string('name');
            $table->string('make');
            $table->string('model');
            $table->unsignedSmallInteger('year');

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->decimal('daily_rate', 10, 2);
            $table->unsignedTinyInteger('seats')->default(4);
            $table->enum('transmission', ['manual', 'automatic'])->default('automatic');
            $table->string('fuel_type')->default('petrol');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();

            $table->enum('status', ['available', 'maintenance', 'retired'])
                ->default('available')
                ->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
