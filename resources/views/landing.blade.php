@extends('layouts.app')

@section('title', 'BagiTugas - Manajemen Tugas Kolaboratif')

@section('content')
<!-- Hero Section -->
<section class="gradient-bg min-h-screen flex items-center pt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Text Content -->
            <div class="text-white">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                    Bagi <span class="text-yellow-300">Tugas</span>
                </h1>
                <p class="text-2xl md:text-3xl mb-4 font-light">
                    "Klik Tugasnya Gas Kerjanya."
                </p>
                <p class="text-lg text-indigo-100 mb-8 max-w-lg">
                    Kelola proyek dan tugas tim Anda dengan mudah. BagiTugas membantu Anda mengorganisir pekerjaan, memantau progress, dan mencapai target tepat waktu.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('login') }}" class="bg-white text-indigo-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-gray-100 transition shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </a>
                    <a href="{{ route('register') }}" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white hover:text-indigo-600 transition">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Gratis
                    </a>
                </div>
            </div>
            
            <!-- Illustration -->
            <div class="hidden lg:block">
                <div class="relative">
                    <!-- Decorative circles -->
                    <div class="absolute -top-10 -left-10 w-32 h-32 bg-white opacity-10 rounded-full"></div>
                    <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-white opacity-10 rounded-full"></div>
                    
                    <!-- Main Card -->
                    <div class="bg-white rounded-2xl shadow-2xl p-8 relative z-10">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-gray-800 font-bold text-xl">Proyek Aktif</h3>
                                <p class="text-gray-500 text-sm">5 Proyek Berjalan</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-chart-line text-green-600 text-xl"></i>
                            </div>
                        </div>
                        
                        <!-- Progress Bars -->
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Website E-Commerce</span>
                                    <span class="text-indigo-600 font-semibold">75%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full" style="width: 75%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Mobile App</span>
                                    <span class="text-green-600 font-semibold">90%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 90%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Dashboard Admin</span>
                                    <span class="text-yellow-600 font-semibold">45%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 45%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                Kenapa Memilih <span class="text-indigo-600">BagiTugas</span>?
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                BagiTugas adalah sistem manajemen proyek dan tugas kolaboratif berbasis web yang dirancang untuk memudahkan pembagian tugas dalam tim.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="card-hover bg-gray-50 rounded-2xl p-8 text-center">
                <div class="w-16 h-16 gradient-bg rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-project-diagram text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Kelola Proyek</h3>
                <p class="text-gray-600">
                    Buat dan kelola multiple proyek dengan mudah. Atur deadline dan pantau progress secara real-time.
                </p>
            </div>
            
            <!-- Feature 2 -->
            <div class="card-hover bg-gray-50 rounded-2xl p-8 text-center">
                <div class="w-16 h-16 gradient-bg rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-tasks text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Bagi Tugas</h3>
                <p class="text-gray-600">
                    Bagikan tugas ke anggota tim dengan jelas. Setiap orang tahu apa yang harus dikerjakan.
                </p>
            </div>
            
            <!-- Feature 3 -->
            <div class="card-hover bg-gray-50 rounded-2xl p-8 text-center">
                <div class="w-16 h-16 gradient-bg rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-chart-pie text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Pantau Progress</h3>
                <p class="text-gray-600">
                    Lihat progress proyek dengan visualisasi yang jelas. Identifikasi hambatan dengan cepat.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 gradient-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
            <div>
                <div class="text-4xl md:text-5xl font-bold mb-2">1000+</div>
                <div class="text-indigo-200">Pengguna Aktif</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold mb-2">500+</div>
                <div class="text-indigo-200">Proyek Selesai</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold mb-2">10000+</div>
                <div class="text-indigo-200">Tugas Terkelola</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold mb-2">99%</div>
                <div class="text-indigo-200">Kepuasan Pengguna</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">
            Siap Untuk Memulai?
        </h2>
        <p class="text-gray-600 text-lg mb-8">
            Bergabunglah dengan ribuan tim yang sudah menggunakan BagiTugas untuk mengelola proyek mereka.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}" class="gradient-bg text-white px-8 py-4 rounded-xl font-semibold text-lg hover:opacity-90 transition shadow-lg">
                <i class="fas fa-rocket mr-2"></i>Daftar Sekarang
            </a>
            <a href="{{ route('login') }}" class="bg-white text-indigo-600 border-2 border-indigo-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-indigo-50 transition">
                <i class="fas fa-sign-in-alt mr-2"></i>Masuk
            </a>
        </div>
    </div>
</section>
@endsection
