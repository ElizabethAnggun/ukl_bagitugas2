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
        'proof_file',
        'completed_at',
    ];

    /**
     * Casting tipe data otomatis
     */
    protected $casts = [
        'deadline' => 'date',
        'completed_at' => 'datetime',
        'proof_file' => 'array',
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
     * Relasi: Task memiliki banyak komentar
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Cek apakah tugas terlambat
     * @return bool
     */
    public function isLate(): bool
    {
        // Bandingkan deadline dengan hari ini atau waktu penyelesaian jika sudah selesai
        $compareDate = ($this->status === 'selesai') ? ($this->completed_at ?? $this->updated_at) : Carbon::now();
        
        return $compareDate->gt($this->deadline->endOfDay());
    }

    /**
     * Get status badge color untuk UI
     * @return string Class warna Tailwind
     */
    public function getStatusColorAttribute(): string
    {
        if ($this->isLate() && $this->status !== 'selesai') {
            return 'bg-red-100 text-red-800';
        }

        return match($this->status) {
            'belum_mulai' => 'bg-gray-100 text-gray-800',
            'berjalan' => 'bg-blue-100 text-blue-800',
            'selesai' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get status label dalam Bahasa Indonesia
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->isLate() && $this->status !== 'selesai') {
            return 'Terlambat';
        }

        if ($this->isLate() && $this->status === 'selesai') {
            return 'Selesai (Terlambat)';
        }

        return match($this->status) {
            'belum_mulai' => 'Belum Mulai',
            'berjalan' => 'Berjalan',
            'selesai' => 'Selesai',
            default => 'Unknown',
        };
    }
}
