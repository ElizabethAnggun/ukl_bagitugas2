@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] flex items-center justify-center px-6 md:px-10 pt-20">
    <div class="max-w-[1200px] mx-auto w-full grid md:grid-cols-2 gap-10 items-center">
        <div class="text-white">
            <h1 class="text-5xl md:text-6xl font-bold mb-8 leading-tight">Apa sih BagiTugas?</h1>
            <p class="text-xl leading-relaxed mb-10 opacity-90">
                <span class="bg-blue-800 px-2 py-1 rounded font-semibold">BagiTugas</span> adalah Sistem Manajemen Proyek dan Tugas Kolaboratif berbasis web yang berfungsi untuk membantu tim mengatur pekerjaan, memonitor progress, dan berkomunikasi dalam satu platform terintegrasi.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('register') }}" class="bg-[#2F5CB4] text-white px-10 py-3 rounded-full text-xl font-bold hover:bg-blue-700 hover:-translate-y-1 transition-all">Daftar</a>
                <a href="{{ route('login') }}" class="bg-[#2F5CB4] text-white px-10 py-3 rounded-full text-xl font-bold hover:bg-blue-700 hover:-translate-y-1 transition-all">Masuk</a>
            </div>
        </div>
        
        <div class="flex justify-center md:justify-end">
            <img src="{{ asset('images/icon_UKL.png') }}" alt="Mascot" class="mascot-about">
        </div>
    </div>
</div>

<style>
    /* Menyamakan ukuran dan animasi persis seperti di landing.blade.php */
    .mascot-about {
        width: 550px;
        height: auto;
        animation: float 4s ease-in-out infinite;
        pointer-events: none;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    
    /* Responsif untuk layar kecil / tablet */
    @media (max-width: 1024px) {
        .mascot-about { 
            width: 400px; 
        }
    }
</style>
@endsection