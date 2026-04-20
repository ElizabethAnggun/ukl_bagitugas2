<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Model Task - Representasi tabel tasks
 * Model ini menyimpan data tugas dan relasinya
 */
class Task extends Model
{
    use HasFactory;

    /**
     * Field yang boleh diisi massal
     */
    protected $fillable = [
        'title',
        'project_id',
        'user_id',
        'deadline',
        'status',
    ];

    /**
     * Casting tipe data otomatis
     */
    protected $casts = [
        'deadline' => 'date',
    ];

    /**
     * Relasi: Task dimiliki oleh satu project
     * Many to One relationship
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relasi: Task dimiliki oleh satu user (yang ditugaskan)
     * Many to One relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Task memiliki banyak log aktivitas
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Cek apakah tugas terlambat
     * @return bool
     */
    public function isLate(): bool
    {
        // Jika sudah selesai, tidak terlambat
        if ($this->status === 'selesai') {
            return false;
        }
        
        // Bandingkan deadline dengan hari ini
        return Carbon::now()->gt($this->deadline);
    }

    /**
     * Update status otomatis jika terlambat
     */
    public function updateStatusIfLate(): void
    {
        if ($this->isLate() && $this->status !== 'terlambat') {
            $this->update(['status' => 'terlambat']);
        }
    }

    /**
     * Get status badge color untuk UI
     * @return string Class warna Tailwind
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'belum_mulai' => 'bg-gray-100 text-gray-800',
            'berjalan' => 'bg-blue-100 text-blue-800',
            'selesai' => 'bg-green-100 text-green-800',
            'terlambat' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get status label dalam Bahasa Indonesia
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'belum_mulai' => 'Belum Mulai',
            'berjalan' => 'Berjalan',
            'selesai' => 'Selesai',
            'terlambat' => 'Terlambat',
            default => 'Unknown',
        };
    }
}
