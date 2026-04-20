<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk membuat tabel tasks
 * Tabel ini menyimpan data tugas yang terkait dengan proyek
 */
return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel tasks
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title'); // Judul tugas
            $table->foreignId('project_id') // Foreign key ke tabel projects
                  ->constrained()
                  ->onDelete('cascade'); // Hapus tugas jika proyek dihapus
            $table->foreignId('user_id') // Foreign key ke tabel users (yang ditugaskan)
                  ->constrained()
                  ->onDelete('cascade');
            $table->date('deadline'); // Deadline tugas
            $table->enum('status', ['belum_mulai', 'berjalan', 'selesai', 'terlambat'])
                  ->default('belum_mulai'); // Status tugas
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Rollback migration - hapus tabel tasks
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
