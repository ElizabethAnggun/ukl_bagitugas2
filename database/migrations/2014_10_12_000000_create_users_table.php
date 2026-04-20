<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk membuat tabel users
 * Tabel ini menyimpan data pengguna aplikasi BagiTugas
 */
return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel users
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key auto increment
            $table->string('name'); // Nama lengkap user
            $table->string('email')->unique(); // Email harus unik
            $table->timestamp('email_verified_at')->nullable(); // Untuk verifikasi email
            $table->string('password'); // Password terenkripsi
            $table->rememberToken(); // Token untuk "remember me"
            $table->timestamps(); // created_at dan updated_at otomatis
        });
    }

    /**
     * Rollback migration - hapus tabel users
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
