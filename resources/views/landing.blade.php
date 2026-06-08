@extends('layouts.app')

@section('content')
<style>
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
        
        /* 3 Baris ini adalah KUNCI agar konten ketengah di layar lebar */
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        
        padding: 120px 40px 60px 40px; /* Padding kiri-kanan dikecilkan jadi 40px */
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
        transition: transform 0.2s ease, background 0.2s;
    }
    .btn-main:hover {
        background-color: #1e428a;
        transform: translateY(-3px);
    }
    
    .hero-image {
        position: relative;
        z-index: 10;
        pointer-events: none;
    }
    
    .hero-mascot {
        width: 550px;
        height: auto;
        animation: float 4s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    
    /* Responsif untuk layar kecil/tablet */
    @media (max-width: 1024px) {
        .hero-section {
            flex-direction: column;
            text-align: center;
            justify-content: center;
            gap: 40px;
            padding: 120px 20px 40px 20px;
        }
        .hero-title { font-size: 5rem; }
        .hero-mascot { width: 380px; }
        .hero-action {
            align-items: center !important; /* Tombol pindah ke tengah */
        }
    }
</style>

<div class="bg-gradient-main">
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Bagi<br>Tugas</h1>
            
            <!-- Kuncinya di sini: buang flex-col, sisakan flex items-center agar sejajar menyamping -->
            <div class="hero-action flex items-center mt-8 gap-6">
                <p class="hero-subtitle mb-0 text-left">Klik Tugasnya<br>Gas Kerjanya.</p>
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
                <p class="text-gray-600">Visualisasi progress yang memudahkan idenfitikasi hambatan.</p>
            </div>
        </div>
    </div>
</section>
@endsection