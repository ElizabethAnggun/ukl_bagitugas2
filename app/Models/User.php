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

    /**
     * Permintaan pertemanan yang dikirim
     */
    public function sentFriendRequests()
    {
        return $this->hasMany(Friend::class, 'sender_id');
    }

    /**
     * Permintaan pertemanan yang diterima
     */
    public function receivedFriendRequests()
    {
        return $this->hasMany(Friend::class, 'receiver_id');
    }

    /**
     * Daftar teman (Accepted)
     */
    public function friends()
    {
        // Teman di mana user sebagai pengirim
        $friendsAsSender = $this->belongsToMany(User::class, 'friends', 'sender_id', 'receiver_id')
            ->wherePivot('status', 'accepted');

        // Teman di mana user sebagai penerima
        $friendsAsReceiver = $this->belongsToMany(User::class, 'friends', 'receiver_id', 'sender_id')
            ->wherePivot('status', 'accepted');

        return $friendsAsSender->union($friendsAsReceiver);
    }

    /**
     * Cek apakah berteman dengan user lain
     */
    public function isFriendsWith($userId)
    {
        return Friend::where(function($q) use ($userId) {
                $q->where('sender_id', $this->id)->where('receiver_id', $userId);
            })
            ->orWhere(function($q) use ($userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $this->id);
            })
            ->where('status', 'accepted')
            ->exists();
    }
}
