<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User - Representasi tabel users
 * Model ini digunakan untuk autentikasi dan manajemen pengguna
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Field yang boleh diisi massal (mass assignment)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Field yang disembunyikan saat serialisasi (JSON)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data otomatis
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Password otomatis di-hash
        ];
    }

    /**
     * Relasi: User memiliki banyak tugas (tasks)
     * One to Many relationship
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Relasi: User memiliki banyak proyek (projects)
     * One to Many relationship
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Relasi: User memiliki banyak log aktivitas
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Relasi: User memiliki banyak komentar
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relasi: User memiliki banyak notifikasi
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
