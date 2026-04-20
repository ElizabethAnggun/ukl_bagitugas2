<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk membuat tabel projects
 * Tabel ini menyimpan data proyek yang dibuat oleh user
 */
return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel projects
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Nama proyek
            $table->text('description')->nullable(); // Deskripsi proyek (boleh kosong)
            $table->date('start_date'); // Tanggal mulai proyek
            $table->date('deadline'); // Deadline proyek
            $table->foreignId('user_id') // Foreign key ke tabel users (pemilik proyek)
                  ->constrained()
                  ->onDelete('cascade'); // Hapus proyek jika user dihapus
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Rollback migration - hapus tabel projects
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
