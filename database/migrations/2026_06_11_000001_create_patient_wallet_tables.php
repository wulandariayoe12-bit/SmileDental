<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('patient_wallets')) {
            Schema::create('patient_wallets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->unique()->constrained('pasien')->cascadeOnDelete();
                $table->unsignedBigInteger('balance')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('wallet_transactions')) {
            Schema::create('wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained('pasien')->cascadeOnDelete();
                $table->foreignId('appointment_id')->nullable()->constrained('janji_temu')->nullOnDelete();
                $table->string('type', 30);
                $table->string('provider', 50)->default('OnoPay');
                $table->unsignedBigInteger('amount');
                $table->string('status', 30)->default('pending');
                $table->string('reference')->unique();
                $table->text('qris_payload')->nullable();
                $table->string('qris_image_url')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['patient_id', 'status']);
                $table->index(['patient_id', 'type']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('patient_wallets');
    }
};
