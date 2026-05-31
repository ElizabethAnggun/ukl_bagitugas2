<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BagiTugas')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="absolute top-0 left-0 w-full z-50">
        <!-- Pengecilan padding di mobile (px-6) dan normal di desktop (md:px-[80px]) -->
        <div class="flex justify-between items-center px-6 md:px-[80px] py-[20px] md:py-[25px]">

            <!-- Logo -->
            <a href="{{ route('landing') }}" class="text-white text-[1.5rem] md:text-[1.8rem] font-bold z-50">
                BagiTugas.
            </a>

            <!-- Tombol Hamburger Menu (Hanya Muncul di Mobile) -->
            <button id="menu-toggle" class="text-white text-2xl md:hidden z-50 focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Menu (Desktop & Mobile) -->
            <ul id="main-menu" class="hidden md:flex flex-col md:flex-row absolute md:static top-[75px] left-4 right-4 bg-[#1450a3] md:bg-transparent p-6 md:p-0 rounded-2xl shadow-xl md:shadow-none gap-4 md:gap-[40px] items-center text-center">

                <li class="w-full md:w-auto">
                    <a href="{{ route('landing') }}" class="text-white text-[1.1rem] font-bold block py-2 md:py-0">
                        Beranda
                    </a>
                </li>

                <li class="w-full md:w-auto">
                    <a href="{{ route('about') }}" class="text-white text-[1.1rem] font-semibold block py-2 md:py-0">
                        Tentang Kami
                    </a>
                </li>

                <li class="w-full md:w-auto">
                    <a href="{{ route('contact') }}" class="text-white text-[1.1rem] font-semibold block py-2 md:py-0">
                        Contact
                    </a>
                </li>

                <li class="w-full md:w-auto mt-2 md:mt-0">
                    <a href="{{ route('login') }}" class="bg-[#2F5CB4] hover:bg-[#1e428a] text-white px-[30px] py-[10px] rounded-full text-[1.1rem] font-semibold inline-block w-full md:w-auto transition">
                        Masuk
                    </a>
                </li>

            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <span class="text-xl font-bold">BagiTugas.</span>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Sistem manajemen proyek dan tugas kolaboratif berbasis web yang dirancang untuk memudahkan pembagian tugas dalam tim.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Tautan Cepat</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('landing') }}" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="#about" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Daftar</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div id="contact">
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i> support@bagitugas.com</li>
                        <li><i class="fas fa-phone mr-2"></i> +62 895 6052 74321</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Sidoarjo, Indonesia</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} BagiTugas. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- JavaScript Toggle Hamburger Menu -->
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mainMenu = document.getElementById('main-menu');

        menuToggle.addEventListener('click', () => {
            // Menambah/menghapus class untuk memunculkan menu sebagai kolom di mobile
            mainMenu.classList.toggle('hidden');
            mainMenu.classList.toggle('flex');
        });
    </script>
    
    <!-- Flash Messages -->
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif
    
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
        });
    </script>
    @endif
    
    @stack('scripts')
</body>
</html>