<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ActivityLog - Representasi tabel activity_logs
 * Model ini menyimpan riwayat aktivitas tugas
 */
class ActivityLog extends Model
{
    use HasFactory;

    /**
     * Field yang boleh diisi massal
     */
    protected $fillable = [
        'user_id',
        'task_id',
        'activity',
        'status',
    ];

    /**
     * Relasi: Log dimiliki oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Log dimiliki oleh satu task
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get status badge color untuk UI
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'selesai' => 'text-green-600',
            'terlambat' => 'text-red-600',
            default => 'text-gray-600',
        };
    }

    /**
     * Get icon berdasarkan status
     */
    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'selesai' => '✓',
            'terlambat' => '⚠',
            default => '•',
        };
    }
}
