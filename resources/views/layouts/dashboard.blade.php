<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | BagiTugas</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar-link {
            transition: all 0.3s ease;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            border-right: 3px solid #6366f1;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg hidden md:flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-tasks text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-800">Bagi<span class="text-indigo-600">Tugas</span></span>
                </a>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-600 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home w-6"></i>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('projects.index') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-600 {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                            <i class="fas fa-folder w-6"></i>
                            <span>Proyek</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tasks.index') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-600 {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list w-6"></i>
                            <span>Tugas</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('activities.index') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-600 {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                            <i class="fas fa-history w-6"></i>
                            <span>Riwayat</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('notifications.index') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-600 {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                            <div class="relative">
                                <i class="fas fa-bell w-6"></i>
                                @php
                                    $unreadCount = Auth::user()->notifications()->where('is_read', false)->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="absolute -top-1 -left-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </div>
                            <span>Notifikasi</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <!-- User Info -->
            <div class="p-4 border-t bg-gray-50">
                <div class="flex items-center">
                    <div class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 text-sm text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Header (Mobile) -->
            <header class="bg-white shadow-sm md:hidden">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 gradient-bg rounded-lg flex items-center justify-center mr-2">
                            <i class="fas fa-tasks text-white text-sm"></i>
                        </div>
                        <span class="font-bold text-gray-800">Bagi<span class="text-indigo-600">Tugas</span></span>
                    </div>
                    <button id="mobile-menu-btn" class="text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
                
                <!-- Mobile Menu -->
                <div id="mobile-menu" class="hidden border-t">
                    <nav class="py-2">
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-home w-6"></i> Beranda
                        </a>
                        <a href="{{ route('projects.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-folder w-6"></i> Proyek
                        </a>
                        <a href="{{ route('tasks.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-clipboard-list w-6"></i> Tugas
                        </a>
                        <a href="{{ route('activities.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-history w-6"></i> Riwayat
                        </a>
                        <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-bell w-6"></i> Notifikasi
                            @if($unreadCount > 0)
                                <span class="ml-1 bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                    </nav>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Mobile Menu Toggle -->
    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
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
