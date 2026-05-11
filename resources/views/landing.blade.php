@extends('layouts.app')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BagiTugas - Klik Tugasnya Gas Kerjanya</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }
        .bg-gradient-main {
            background: linear-gradient(135deg, #1d61bd 0%, #0ea0d8 100%);
            width: 100%;
            min-height: 100vh;
            color: #ffffff;
            display: flex;
            flex-direction: column;
        }
        
        /* Hero Styling */
        .hero-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 80px 60px 80px;
        }
        .hero-title {
            font-size: 8.5rem;
            font-weight: 900;
            line-height: 0.9;
            letter-spacing: -4px;
        }
        .hero-subtitle {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.2;
            margin-right: 30px;
        }
        .btn-main {
            background-color: #2F5CB4;
            color: #ffffff;
            padding: 15px 50px;
            border-radius: 50px;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
        }
        .hero-mascot {
            width: 600px;
            height: auto;
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        @media (max-width: 1024px) {
            .hero-title { font-size: 5rem; }
            .hero-mascot { width: 400px; }
            .custom-navbar, .hero-section { padding: 20px 40px; }
        }
    </style>
</head>
<body>

    <div class="bg-gradient-main">

        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Bagi<br>Tugas.</h1>
                <div class="flex items-center mt-10">
                    <p class="hero-subtitle">Klik Tugasnya<br>Gas Kerjanya.</p>
                    <a href="{{ route('login') }}" class="btn-main">Masuk</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="{{ asset('images/icon_UKL.png') }}" alt="Mascot" class="hero-mascot">
            </div>
        </section>
    </div>

    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-4">Kenapa Memilih <span class="text-blue-600">BagiTugas</span>?</h2>
            <p class="text-gray-500 mb-16 max-w-2xl mx-auto">Sistem manajemen proyek kolaboratif untuk memudahkan pembagian tugas tim secara efisien.</p>
            
            <div class="grid md:grid-cols-3 gap-10">
                <div class="p-10 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white text-2xl mx-auto mb-6"><i class="fas fa-layer-group"></i></div>
                    <h3 class="text-xl font-bold mb-3">Kelola Proyek</h3>
                    <p class="text-gray-600">Atur deadline dan pantau progress setiap proyek secara real-time.</p>
                </div>
                <div class="p-10 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center text-white text-2xl mx-auto mb-6"><i class="fas fa-users"></i></div>
                    <h3 class="text-xl font-bold mb-3">Bagi Tugas</h3>
                    <p class="text-gray-600">Delegasikan tugas ke anggota tim dengan instruksi yang jelas.</p>
                </div>
                <div class="p-10 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-blue-400 rounded-2xl flex items-center justify-center text-white text-2xl mx-auto mb-6"><i class="fas fa-chart-line"></i></div>
                    <h3 class="text-xl font-bold mb-3">Pantau Progress</h3>
                    <p class="text-gray-600">Visualisasi progress yang memudahkan identifikasi hambatan.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-12">
            <div>
                <h3 class="text-2xl font-bold mb-4">BagiTugas.</h3>
                <p class="text-slate-400">Klik Tugasnya, Gas Kerjanya. Solusi manajemen tugas pelajar masa kini.</p>
            </div>
            <div>
                <h4 class="font-bold mb-4">Tautan Cepat</h4>
                <ul class="text-slate-400 space-y-2">
                    <li><a href="#" class="hover:text-white">Beranda</a></li>
                    <li><a href="#" class="hover:text-white">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-white">Masuk</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4">Kontak</h4>
                <p class="text-slate-400"><i class="fas fa-envelope mr-2"></i> support@bagitugas.com</p>
                <p class="text-slate-400 mt-2"><i class="fas fa-map-marker-alt mr-2"></i> Sidoarjo, Jawa Timur</p>
            </div>
        </div>
        <div class="text-center mt-12 pt-8 border-t border-slate-800 text-slate-500">
            &copy; 2026 BagiTugas. All rights reserved.
        </div>
    </footer>

</body>
</html>