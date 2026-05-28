@extends('layouts.app')

@section('title', 'Daftar - BagiTugas')

@section('content')
<div class="min-h-screen py-20 pt-32 flex items-center justify-center bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] px-6 overflow-hidden">

    <div class="w-full max-w-6xl flex items-center justify-between relative scale-[0.88] origin-center">

        <!-- LEFT SIDE -->
        <div class="hidden md:flex flex-col items-center text-white w-1/2 relative">

            <!-- Blur Effect Background -->
            <div class="absolute w-[500px] h-[500px] bg-white/10 blur-3xl rounded-full"></div>

            <h1 class="text-5xl font-bold mb-8 relative z-10 drop-shadow-lg">
                Ayo Daftar Sekarang !
            </h1>
            
            <!-- ICON -->
            <img src="{{ asset('images/icon_ukl_v2.png') }}" 
                 alt="Illustration" 
                 class="w-[520px] drop-shadow-2xl relative z-10">
        </div>

        <!-- RIGHT SIDE -->
        <div class="w-full md:w-1/2 flex justify-center">

            <!-- GLASS CARD -->
            <div class="backdrop-blur-xl bg-white/20 border border-white/30 rounded-[36px] shadow-2xl p-6 w-full max-w-[430px]">

                <h2 class="text-4xl font-bold text-center text-white mb-8 drop-shadow-md">
                    Daftar
                </h2>

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <!-- Nama -->
                    <div class="mb-5">
                        <label class="block text-lg font-semibold mb-2 text-white">
                            Nama
                        </label>

                        <input type="text" 
                               name="name"
                               value="{{ old('name') }}"
                               class="w-full bg-white/30 border border-white/40 text-white placeholder-white/70 px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-white backdrop-blur-md @error('name') border-red-400 @enderror"
                               required>

                        @error('name')
                            <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-5">
                        <label class="block text-lg font-semibold mb-2 text-white">
                            Email
                        </label>

                        <input type="email" 
                               name="email"
                               value="{{ old('email') }}"
                               class="w-full bg-white/30 border border-white/40 text-white placeholder-white/70 px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-white backdrop-blur-md @error('email') border-red-400 @enderror"
                               required>

                        @error('email')
                            <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-5">
                        <label class="block text-lg font-semibold mb-2 text-white">
                            Password
                        </label>

                        <input type="password" 
                               name="password"
                               class="w-full bg-white/30 border border-white/40 text-white placeholder-white/70 px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-white backdrop-blur-md @error('password') border-red-400 @enderror"
                               required>

                        @error('password')
                            <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-6">
                        <label class="block text-lg font-semibold mb-2 text-white">
                            Konfirmasi Password
                        </label>

                        <input type="password" 
                               name="password_confirmation"
                               class="w-full bg-white/30 border border-white/40 text-white placeholder-white/70 px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-white backdrop-blur-md"
                               required>
                    </div>

                    <!-- Button -->
                    <button type="submit"
                        class="w-full bg-[#2F5CB4] hover:bg-[#264d96] text-white py-4 rounded-2xl text-2xl font-bold shadow-xl transition duration-300">
                        Daftar
                    </button>
                </form>

                <!-- Login -->
                <p class="text-center mt-6 text-white text-base">
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