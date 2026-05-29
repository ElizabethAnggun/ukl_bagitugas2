@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] flex items-center justify-center px-10 pt-20">
    <div class="max-w-6xl w-full grid md:grid-cols-2 gap-10 items-center">
        <div class="text-white">
            <h1 class="text-5xl font-bold mb-8">Apa sih BagiTugas. ?</h1>
            <p class="text-xl leading-relaxed mb-10 opacity-90">
                <span class="bg-blue-800 px-2 rounded">BagiTugas.</span> adalah Sistem Manajemen Proyek dan Tugas Kolaboratif berbasis web yang berfungsi untuk membantu tim mengatur pekerjaan, memonitor progress, dan berkomunikasi dalam satu platform terintegrasi.
            </p>
            <div class="flex gap-4">
                <a href="{{ route('register') }}" class="bg-[#2F5CB4] text-white px-10 py-3 rounded-full text-xl font-bold hover:bg-blue-700 transition">Daftar</a>
                <a href="{{ route('login') }}" class="bg-[#2F5CB4] text-white px-10 py-3 rounded-full text-xl font-bold hover:bg-blue-700 transition">Masuk</a>
            </div>
        </div>
        <div class="flex justify-center">
            <img src="{{ asset('images/icon_UKL.png') }}" alt="Mascot" class="w-[450px] animate-bounce-slow">
        </div>
    </div>
</div>

<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    .animate-bounce-slow { animation: bounce-slow 4s ease-in-out infinite; }
</style>
@endsection