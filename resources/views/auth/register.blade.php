@extends('layouts.app')

@section('title', 'Daftar - BagiTugas')

@section('content')
<div class="min-h-screen pt-24 flex items-center justify-center bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] px-6 overflow-hidden">

        <div class="w-full max-w-5xl flex items-center justify-between gap-10 scale-[0.92]">

            <div class="hidden md:flex flex-col items-center justify-center text-white w-1/2 mt-10">

                <h1 class="text-5xl font-bold mb-2 drop-shadow-lg leading-tight">
                    Ayo Daftar !
                </h1>

                <img src="{{ asset('images/icon_ukl_v2.png') }}" 
                    alt="Illustration" 
                    class="w-[430px] translate-y-2 drop-shadow-[0_0_45px_rgba(255,255,255,0.25)]">
        </div>

        <!-- RIGHT SIDE -->
        <div class="w-full md:w-1/2 flex justify-center">

            <!-- GLASS CARD -->
            <div class="backdrop-blur-xl bg-white/20 border border-white/20 rounded-[35px] shadow-2xl p-8 w-full max-w-[430px]">

                <!-- TITLE -->
                <h2 class="text-5xl font-bold text-center text-white mb-8 drop-shadow-md">
                    Daftar
                </h2>

                <!-- FORM -->
                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <!-- NAMA -->
                    <div class="mb-5">
                        <label class="block text-white text-lg font-semibold mb-2">
                            Nama
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Masukkan nama..."
                               class="w-full bg-white/30 border border-white/20 text-white placeholder-white/70 px-5 py-3 rounded-2xl focus:outline-none focus:ring-2 focus:ring-white/60 @error('name') border-red-400 @enderror"
                               required>

                        @error('name')
                            <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- EMAIL -->
                    <div class="mb-5">
                        <label class="block text-white text-lg font-semibold mb-2">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Masukkan email..."
                               class="w-full bg-white/30 border border-white/20 text-white placeholder-white/70 px-5 py-3 rounded-2xl focus:outline-none focus:ring-2 focus:ring-white/60 @error('email') border-red-400 @enderror"
                               required>

                        @error('email')
                            <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- PASSWORD -->
                    <div class="mb-5">
                        <label class="block text-white text-lg font-semibold mb-2">
                            Password
                        </label>

                        <input type="password"
                               name="password"
                               placeholder="Masukkan password..."
                               class="w-full bg-white/30 border border-white/20 text-white placeholder-white/70 px-5 py-3 rounded-2xl focus:outline-none focus:ring-2 focus:ring-white/60 @error('password') border-red-400 @enderror"
                               required>

                        @error('password')
                            <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- KONFIRMASI PASSWORD -->
                    <div class="mb-6">
                        <label class="block text-white text-lg font-semibold mb-2">
                            Konfirmasi Password
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               placeholder="Ulangi password..."
                               class="w-full bg-white/30 border border-white/20 text-white placeholder-white/70 px-5 py-3 rounded-2xl focus:outline-none focus:ring-2 focus:ring-white/60"
                               required>
                    </div>

                    <!-- BUTTON -->
                    <button type="submit"
                        class="w-full bg-[#365fbf] hover:bg-[#2d52a7] text-white py-3 rounded-2xl text-2xl font-bold transition shadow-xl mb-4">
                        Daftar
                    </button>
                </form>

                <!-- LOGIN -->
                <p class="text-center mt-6 text-white text-lg">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                       class="font-bold hover:underline">
                        Masuk Sekarang
                    </a>
                </p>

            </div>
        </div>

    </div>
</div>
@endsection