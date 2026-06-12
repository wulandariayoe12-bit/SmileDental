<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pasien')) {
            Schema::create('pasien', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->date('tanggal_lahir')->nullable();
                $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
                $table->text('alamat')->nullable();
                $table->string('no_hp')->nullable();
                $table->text('riwayat_penyakit')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('dokter')) {
            Schema::create('dokter', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('spesialisasi')->nullable();
                $table->string('no_hp')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('layanan_klinik')) {
            Schema::create('layanan_klinik', function (Blueprint $table) {
                $table->id();
                $table->string('nama_layanan');
                $table->text('deskripsi')->nullable();
                $table->unsignedBigInteger('harga')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('jadwal_dokter')) {
            Schema::create('jadwal_dokter', function (Blueprint $table) {
                $table->id();
                $table->foreignId('doctor_id')->constrained('dokter')->cascadeOnDelete();
                $table->string('hari');
                $table->time('jam_mulai');
                $table->time('jam_selesai');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('janji_temu')) {
            Schema::create('janji_temu', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained('pasien')->cascadeOnDelete();
                $table->foreignId('doctor_id')->constrained('dokter')->cascadeOnDelete();
                $table->date('tanggal');
                $table->time('jam');
                $table->enum('status', ['pending', 'selesai', 'batal'])->default('pending');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('rekam_medis')) {
            Schema::create('rekam_medis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained('pasien')->cascadeOnDelete();
                $table->foreignId('doctor_id')->constrained('dokter')->cascadeOnDelete();
                $table->foreignId('appointment_id')->nullable()->constrained('janji_temu')->nullOnDelete();
                $table->string('diagnosa');
                $table->string('tindakan')->nullable();
                $table->text('catatan')->nullable();
                $table->date('tanggal');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('pembayaran')) {
            Schema::create('pembayaran', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained('pasien')->cascadeOnDelete();
                $table->foreignId('appointment_id')->nullable()->constrained('janji_temu')->nullOnDelete();
                $table->unsignedBigInteger('total_harga')->default(0);
                $table->string('metode_pembayaran')->default('Cash');
                $table->enum('status', ['lunas', 'belum'])->default('belum');
                $table->date('tanggal');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('rekam_medis');
        Schema::dropIfExists('janji_temu');
        Schema::dropIfExists('jadwal_dokter');
        Schema::dropIfExists('layanan_klinik');
        Schema::dropIfExists('dokter');
        Schema::dropIfExists('pasien');
    }
};
