<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'patient_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('patient_id')
                    ->nullable()
                    ->after('role')
                    ->constrained('pasien')
                    ->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('janji_temu', 'keluhan')) {
            Schema::table('janji_temu', function (Blueprint $table) {
                $table->text('keluhan')->nullable()->after('jam');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('janji_temu', 'keluhan')) {
            Schema::table('janji_temu', function (Blueprint $table) {
                $table->dropColumn('keluhan');
            });
        }

        if (Schema::hasColumn('users', 'patient_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('patient_id');
            });
        }
    }
};
