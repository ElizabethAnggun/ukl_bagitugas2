<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Project - Representasi tabel projects
 * Model ini menyimpan data proyek dan relasinya
 */
class Project extends Model
{
    use HasFactory;

    /**
     * Field yang boleh diisi massal
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'deadline',
        'user_id',
    ];

    /**
     * Casting tipe data otomatis
     */
    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
    ];

    /**
     * Relasi: Project dimiliki oleh satu user (pemilik)
     * Many to One relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Project memiliki banyak tugas (tasks)
     * One to Many relationship
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Relasi: Project memiliki banyak sub pengelola (managers)
     */
    public function managers()
    {
        return $this->belongsToMany(User::class, 'project_managers');
    }

    /**
     * Cek apakah user adalah owner atau sub pengelola
     */
    public function isManager($userId): bool
    {
        if ($this->user_id === $userId) {
            return true;
        }

        return $this->managers()->where('user_id', $userId)->exists();
    }

    /**
     * Hitung progress proyek berdasarkan tugas selesai
     * @return int Persentase progress (0-100)
     */
    public function getProgressAttribute(): int
    {
        $totalTasks = $this->tasks()->count();
        
        // Jika tidak ada tugas, progress 0%
        if ($totalTasks === 0) {
            return 0;
        }
        
        $completedTasks = $this->tasks()->where('status', 'selesai')->count();
        
        // Hitung persentase dan bulatkan
        return round(($completedTasks / $totalTasks) * 100);
    }

    /**
     * Hitung jumlah tugas per proyek
     */
    public function getTotalTasksAttribute(): int
    {
        return $this->tasks()->count();
    }

    /**
     * Hitung jumlah tugas selesai
     */
    public function getCompletedTasksAttribute(): int
    {
        return $this->tasks()->where('status', 'selesai')->count();
    }
}
