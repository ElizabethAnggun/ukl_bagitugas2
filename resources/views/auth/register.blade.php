@extends('layouts.app')

@section('title', 'Daftar - BagiTugas')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-700 to-cyan-500 px-6">

    <div class="w-full max-w-6xl flex items-center justify-between">

        <!-- LEFT SIDE -->
        <div class="hidden md:flex flex-col items-center text-white w-1/2">
            <h1 class="text-4xl font-bold mb-6">BagiTugas.</h1>
            
            <!-- GANTI DENGAN GAMBAR DARI FIGMA -->
            <img src="{{ asset('images/icon_ukl_v2.png') }}" alt="Illustration" class="w-80">
        </div>

        <!-- RIGHT SIDE -->
        <div class="w-full md:w-1/2 flex justify-center">
            <div class="bg-gray-100 rounded-3xl shadow-2xl p-10 w-full max-w-md">

                <h2 class="text-3xl font-bold text-center text-blue-700 mb-8">
                    Daftar
                </h2>

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <!-- Nama -->
                    <div class="mb-5">
                        <label class="block text-lg font-semibold mb-2">Nama</label>
                        <input type="text" 
                               name="name"
                               value="{{ old('name') }}"
                               class="w-full border border-gray-400 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                               required>

                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-5">
                        <label class="block text-lg font-semibold mb-2">Email</label>
                        <input type="email" 
                               name="email"
                               value="{{ old('email') }}"
                               class="w-full border border-gray-400 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                               required>

                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-5">
                        <label class="block text-lg font-semibold mb-2">Password</label>
                        <input type="password" 
                               name="password"
                               class="w-full border border-gray-400 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                               required>

                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-6">
                        <label class="block text-lg font-semibold mb-2">Konfirmasi Password</label>
                        <input type="password" 
                               name="password_confirmation"
                               class="w-full border border-gray-400 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>

                    <!-- Button -->
                    <button type="submit"
                        class="w-full bg-blue-700 text-white py-3 rounded-xl text-lg font-semibold hover:bg-blue-800 transition shadow-md">
                        Lanjut
                    </button>
                </form>

                <!-- Login -->
                <p class="text-center mt-5 text-sm text-gray-700">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-blue-700 font-semibold hover:underline">
                        Masuk Sekarang
                    </a>
                </p>

            </div>
        </div>

    </div>
</div>
@endsection