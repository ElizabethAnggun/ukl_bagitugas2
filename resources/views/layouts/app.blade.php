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
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="absolute top-0 left-0 w-full z-50">
    <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center text-white">

        <!-- Logo -->
        <a href="{{ route('landing') }}" class="text-xl font-bold">
            BagiTugas.
        </a>

        <!-- Menu -->
        <div class="hidden md:flex items-center gap-8">
            <a href="{{ route('landing') }}" class="hover:opacity-80 transition">Beranda</a>
            <a href="#about" class="hover:opacity-80 transition">Tentang Kami</a>
            <a href="#contact" class="hover:opacity-80 transition">Contact</a>

            @auth
                <a href="{{ route('dashboard') }}" 
                   class="bg-blue-600 px-5 py-2 rounded-full hover:bg-blue-700 transition">
                   Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="bg-blue-600 px-5 py-2 rounded-full hover:bg-blue-700 transition">
                   Masuk
                </a>
            @endauth
        </div>

    </div>
</nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-tasks text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold">Bagi<span class="text-indigo-400">Tugas</span></span>
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
                        <li><i class="fas fa-phone mr-2"></i> +62 123 4567 890</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Jakarta, Indonesia</li>
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
