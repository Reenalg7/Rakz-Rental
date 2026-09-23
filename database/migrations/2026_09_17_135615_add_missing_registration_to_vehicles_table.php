<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('vehicles', 'registration')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->string('registration')->nullable()->unique()->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('vehicles', 'registration')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->dropUnique(['registration']);
                $table->dropColumn('registration');
            });
        }
    }
};