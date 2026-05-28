@extends('layouts.app')

@section('title', 'Masuk - BagiTugas')

@section('content')
<div class="min-h-screen pt-24 flex items-center justify-center bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] px-6 overflow-hidden">

    <div class="w-full max-w-5xl flex items-center justify-between gap-10 scale-[0.92]">

        <!-- LEFT SIDE -->
        <div class="hidden md:flex flex-col items-center text-white w-1/2 mt-10">
            <h1 class="text-5xl font-bold mb-2 drop-shadow-lg">
                Selamat Datang !
            </h1>

            <img src="{{ asset('images/icon_ukl_v2.png') }}" 
                 alt="Illustration" 
                 class="w-[430px] drop-shadow-[0_0_45px_rgba(255,255,255,0.25)]">
        </div>

        <!-- RIGHT SIDE -->
        <div class="w-full md:w-1/2 flex justify-center">
            
            <!-- GLASS CARD -->
            <div class="backdrop-blur-xl bg-white/20 border border-white/20 rounded-[35px] shadow-2xl p-8 w-full max-w-[430px]">

                <!-- TITLE -->
                <h2 class="text-5xl font-bold text-center text-white mb-8 drop-shadow-md">
                    Masuk
                </h2>

                <!-- FORM -->
                <form action="{{ route('login') }}" method="POST">
                    @csrf

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
                    <div class="mb-4">
                        <label class="block text-white text-lg font-semibold mb-2">
                            Password
                        </label>

                        <div class="relative">
                            <input type="password"
                                   name="password"
                                   id="password"
                                   placeholder="Masukkan password..."
                                   class="w-full bg-white/30 border border-white/20 text-white placeholder-white/70 px-5 py-3 rounded-2xl focus:outline-none focus:ring-2 focus:ring-white/60 pr-14 @error('password') border-red-400 @enderror"
                                   required>

                            <!-- EYE BUTTON -->
                            <button type="button"
                                    onclick="togglePassword()"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-white/80 hover:text-white">
                                <i class="fas fa-eye" id="eye-icon"></i>
                            </button>
                        </div>

                        @error('password')
                            <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- REMEMBER -->
                    <div class="flex items-center mb-6">
                        <input type="checkbox"
                               name="remember"
                               class="w-4 h-4 rounded border-white/30 bg-white/20 text-blue-600">

                        <span class="ml-2 text-sm text-white">
                            Ingat saya
                        </span>
                    </div>

                    <!-- BUTTON -->
                    <button type="submit"
                        class="w-full bg-[#365fbf] hover:bg-[#2d52a7] text-white py-3 rounded-2xl text-2xl font-bold transition shadow-xl">
                        Masuk
                    </button>
                </form>

                <!-- REGISTER -->
                <p class="text-center mt-6 text-white text-lg">
                    Belum punya akun?
                    <a href="{{ route('register') }}"
                       class="font-bold hover:underline">
                        Daftar Sekarang
                    </a>
                </p>

            </div>
        </div>

    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>
@endsection