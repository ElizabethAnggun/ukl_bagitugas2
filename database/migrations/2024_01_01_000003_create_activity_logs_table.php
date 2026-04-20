<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk membuat tabel activity_logs
 * Tabel ini menyimpan riwayat aktivitas tugas
 */
return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel activity_logs
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id') // Foreign key ke tabel users
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('task_id') // Foreign key ke tabel tasks
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('activity'); // Deskripsi aktivitas
            $table->enum('status', ['selesai', 'terlambat']); // Status saat aktivitas
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Rollback migration - hapus tabel activity_logs
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
