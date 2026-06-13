<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('clinic_locations')) {
            Schema::create('clinic_locations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->text('address')->nullable();
                $table->string('operational_hours')->nullable();
                $table->string('phone')->nullable();
                $table->string('whatsapp')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('doctor_reviews')) {
            Schema::create('doctor_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained('pasien')->cascadeOnDelete();
                $table->foreignId('doctor_id')->constrained('dokter')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->unsignedTinyInteger('rating');
                $table->text('message');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_reviews');
        Schema::dropIfExists('clinic_locations');
    }
};
