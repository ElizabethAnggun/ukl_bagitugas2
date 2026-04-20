<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * DatabaseSeeder - Seeder untuk mengisi data awal
 * 
 * Jalankan dengan: php artisan db:seed
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat user admin
        $admin = User::create([
            'name' => 'Admin BagiTugas',
            'email' => 'admin@bagitugas.com',
            'password' => Hash::make('password'),
        ]);

        // Buat user contoh
        $user1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
        ]);

        $user2 = User::create([
            'name' => 'Ani Wijaya',
            'email' => 'ani@example.com',
            'password' => Hash::make('password'),
        ]);

        // Buat proyek contoh
        $project1 = Project::create([
            'name' => 'Website E-Commerce',
            'description' => 'Pengembangan website e-commerce untuk UMKM lokal dengan fitur keranjang belanja, pembayaran online, dan manajemen produk.',
            'start_date' => '2024-01-01',
            'deadline' => '2024-03-31',
            'user_id' => $admin->id,
        ]);

        $project2 = Project::create([
            'name' => 'Aplikasi Mobile Absensi',
            'description' => 'Aplikasi mobile untuk sistem absensi karyawan dengan fitur GPS dan face recognition.',
            'start_date' => '2024-02-01',
            'deadline' => '2024-04-30',
            'user_id' => $admin->id,
        ]);

        $project3 = Project::create([
            'name' => 'Dashboard Analytics',
            'description' => 'Dashboard untuk monitoring dan analisis data penjualan real-time.',
            'start_date' => '2024-01-15',
            'deadline' => '2024-02-28',
            'user_id' => $admin->id,
        ]);

        // Buat tugas untuk proyek 1
        Task::create([
            'title' => 'Desain UI/UX Homepage',
            'project_id' => $project1->id,
            'user_id' => $user1->id,
            'deadline' => '2024-01-15',
            'status' => 'selesai',
        ]);

        Task::create([
            'title' => 'Implementasi Database',
            'project_id' => $project1->id,
            'user_id' => $user2->id,
            'deadline' => '2024-01-20',
            'status' => 'selesai',
        ]);

        Task::create([
            'title' => 'Fitur Keranjang Belanja',
            'project_id' => $project1->id,
            'user_id' => $user1->id,
            'deadline' => '2024-02-10',
            'status' => 'berjalan',
        ]);

        Task::create([
            'title' => 'Integrasi Payment Gateway',
            'project_id' => $project1->id,
            'user_id' => $user2->id,
            'deadline' => '2024-02-28',
            'status' => 'belum_mulai',
        ]);

        // Buat tugas untuk proyek 2
        Task::create([
            'title' => 'Riset Teknologi Face Recognition',
            'project_id' => $project2->id,
            'user_id' => $user1->id,
            'deadline' => '2024-02-15',
            'status' => 'berjalan',
        ]);

        Task::create([
            'title' => 'Desain Database Absensi',
            'project_id' => $project2->id,
            'user_id' => $user2->id,
            'deadline' => '2024-02-10',
            'status' => 'belum_mulai',
        ]);

        // Buat tugas untuk proyek 3 (selesai)
        Task::create([
            'title' => 'Setup Server dan Environment',
            'project_id' => $project3->id,
            'user_id' => $user1->id,
            'deadline' => '2024-01-20',
            'status' => 'selesai',
        ]);

        Task::create([
            'title' => 'Koneksi ke API Data Source',
            'project_id' => $project3->id,
            'user_id' => $user2->id,
            'deadline' => '2024-01-25',
            'status' => 'selesai',
        ]);

        Task::create([
            'title' => 'Visualisasi Chart dan Grafik',
            'project_id' => $project3->id,
            'user_id' => $user1->id,
            'deadline' => '2024-02-05',
            'status' => 'selesai',
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('Email: admin@bagitugas.com');
        $this->command->info('Password: password');
    }
}
