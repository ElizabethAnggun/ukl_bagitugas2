<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | BagiTugas</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
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
            background: #cbd5e1; /* Warna scrollbar sedikit dilembutkan */
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-slate-50"> <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-white shadow-[4px_0_24px_rgba(0,0,0,0.02)] hidden md:flex flex-col border-r border-gray-100 z-50">
            
            <div class="flex items-center gap-3 px-6 py-6 border-b border-gray-50">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] flex items-center justify-center text-white shadow-md shadow-blue-200 flex-shrink-0">
                    <i class="fas fa-list-ul"></i>
                </div>
                <span class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#1d61bd] to-[#0ea0d8] tracking-tight">
                    BagiTugas
                </span>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-6">
                <ul class="space-y-1.5">
                    <li>
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3 px-6 py-3.5 transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-[#1d61bd]/10 to-[#0ea0d8]/5 border-r-4 border-[#1d61bd] text-[#1d61bd] font-extrabold' : 'text-gray-500 hover:text-[#1d61bd] hover:bg-blue-50/50 font-medium' }}">
                            <i class="fas fa-home text-lg w-6 text-center {{ request()->routeIs('dashboard') ? 'text-[#1d61bd]' : 'text-gray-400' }}"></i>
                            <span>Beranda</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('projects.index') }}" 
                           class="flex items-center gap-3 px-6 py-3.5 transition-all duration-300 {{ request()->routeIs('projects.*') ? 'bg-gradient-to-r from-[#1d61bd]/10 to-[#0ea0d8]/5 border-r-4 border-[#1d61bd] text-[#1d61bd] font-extrabold' : 'text-gray-500 hover:text-[#1d61bd] hover:bg-blue-50/50 font-medium' }}">
                            <i class="fas fa-folder text-lg w-6 text-center {{ request()->routeIs('projects.*') ? 'text-[#1d61bd]' : 'text-gray-400' }}"></i>
                            <span>Proyek</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('tasks.index') }}" 
                           class="flex items-center gap-3 px-6 py-3.5 transition-all duration-300 {{ request()->routeIs('tasks.*') ? 'bg-gradient-to-r from-[#1d61bd]/10 to-[#0ea0d8]/5 border-r-4 border-[#1d61bd] text-[#1d61bd] font-extrabold' : 'text-gray-500 hover:text-[#1d61bd] hover:bg-blue-50/50 font-medium' }}">
                            <i class="fas fa-clipboard-list text-lg w-6 text-center {{ request()->routeIs('tasks.*') ? 'text-[#1d61bd]' : 'text-gray-400' }}"></i>
                            <span>Tugas</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('activities.index') }}" 
                           class="flex items-center gap-3 px-6 py-3.5 transition-all duration-300 {{ request()->routeIs('activities.*') ? 'bg-gradient-to-r from-[#1d61bd]/10 to-[#0ea0d8]/5 border-r-4 border-[#1d61bd] text-[#1d61bd] font-extrabold' : 'text-gray-500 hover:text-[#1d61bd] hover:bg-blue-50/50 font-medium' }}">
                            <i class="fas fa-history text-lg w-6 text-center {{ request()->routeIs('activities.*') ? 'text-[#1d61bd]' : 'text-gray-400' }}"></i>
                            <span>Riwayat</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('friends.index') }}" 
                           class="flex items-center gap-3 px-6 py-3.5 transition-all duration-300 {{ request()->routeIs('friends.*') ? 'bg-gradient-to-r from-[#1d61bd]/10 to-[#0ea0d8]/5 border-r-4 border-[#1d61bd] text-[#1d61bd] font-extrabold' : 'text-gray-500 hover:text-[#1d61bd] hover:bg-blue-50/50 font-medium' }}">
                            <div class="relative w-6 text-center">
                                <i class="fas fa-user-friends text-lg {{ request()->routeIs('friends.*') ? 'text-[#1d61bd]' : 'text-gray-400' }}"></i>
                                @php
                                    $pendingCount = Auth::user()->receivedFriendRequests()->where('status', 'pending')->count();
                                @endphp
                                <div id="live-sidebar-friends-badge">
                                    @if($pendingCount > 0)
                                        <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-orange-500 text-[9px] font-bold text-white shadow-sm ring-2 ring-white">
                                            {{ $pendingCount }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <span>Teman</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('notifications.index') }}" 
                           class="flex items-center gap-3 px-6 py-3.5 transition-all duration-300 {{ request()->routeIs('notifications.*') ? 'bg-gradient-to-r from-[#1d61bd]/10 to-[#0ea0d8]/5 border-r-4 border-[#1d61bd] text-[#1d61bd] font-extrabold' : 'text-gray-500 hover:text-[#1d61bd] hover:bg-blue-50/50 font-medium' }}">
                            <div class="relative w-6 text-center">
                                <i class="fas fa-bell text-lg {{ request()->routeIs('notifications.*') ? 'text-[#1d61bd]' : 'text-gray-400' }}"></i>
                                @php
                                    $unreadCount = Auth::user()->notifications()->where('is_read', false)->count();
                                @endphp
                                <div id="live-sidebar-notifications-badge">
                                    @if($unreadCount > 0)
                                        <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[9px] font-bold text-white shadow-sm ring-2 ring-white">
                                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <span>Notifikasi</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="border-t border-gray-100 p-4 bg-gray-50/50">
                <div class="flex items-center gap-3 px-2 py-2 mb-2">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] flex items-center justify-center text-white font-extrabold text-sm flex-shrink-0 shadow-md">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-extrabold text-gray-800 truncate">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-[11px] font-semibold text-gray-500 truncate">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-rose-500 hover:bg-rose-100 hover:text-rose-600 rounded-xl transition-all font-bold text-sm border border-transparent hover:border-rose-200">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>
        
        <div class="flex-1 flex flex-col overflow-hidden relative">
            
            <header class="bg-white/90 backdrop-blur-md shadow-sm md:hidden sticky top-0 z-50">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] rounded-lg flex items-center justify-center mr-2 shadow-sm">
                            <i class="fas fa-list-ul text-white text-sm"></i>
                        </div>
                        <span class="font-extrabold text-xl text-gray-800 tracking-tight">Bagi<span class="text-[#1d61bd]">Tugas</span></span>
                    </div>
                    <button id="mobile-menu-btn" class="text-gray-500 hover:text-[#1d61bd] transition-colors p-2 rounded-lg bg-gray-50">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
                
                <div id="mobile-menu" class="hidden border-t border-gray-100 absolute w-full bg-white shadow-xl">
                    <nav class="py-2 px-4 space-y-1">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-600 hover:bg-blue-50 hover:text-[#1d61bd] rounded-xl">
                            <i class="fas fa-home w-5 text-center"></i> Beranda
                        </a>
                        <a href="{{ route('projects.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-600 hover:bg-blue-50 hover:text-[#1d61bd] rounded-xl">
                            <i class="fas fa-folder w-5 text-center"></i> Proyek
                        </a>
                        <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-600 hover:bg-blue-50 hover:text-[#1d61bd] rounded-xl">
                            <i class="fas fa-clipboard-list w-5 text-center"></i> Tugas
                        </a>
                        <a href="{{ route('activities.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-600 hover:bg-blue-50 hover:text-[#1d61bd] rounded-xl">
                            <i class="fas fa-history w-5 text-center"></i> Riwayat
                        </a>
                        <a href="{{ route('friends.index') }}" class="flex items-center justify-between px-4 py-3 text-sm font-bold text-gray-600 hover:bg-blue-50 hover:text-[#1d61bd] rounded-xl">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-user-friends w-5 text-center"></i> Teman
                            </div>
                            <div id="live-mobile-friends-badge">
                                @if($pendingCount > 0)
                                    <span class="bg-orange-500 text-white text-[10px] px-2 py-0.5 rounded-full shadow-sm">{{ $pendingCount }} Baru</span>
                                @endif
                            </div>
                        </a>
                        <a href="{{ route('notifications.index') }}" class="flex items-center justify-between px-4 py-3 text-sm font-bold text-gray-600 hover:bg-blue-50 hover:text-[#1d61bd] rounded-xl">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-bell w-5 text-center"></i> Notifikasi
                            </div>
                            <div id="live-mobile-notifications-badge">
                                @if($unreadCount > 0)
                                    <span class="bg-rose-500 text-white text-[10px] px-2 py-0.5 rounded-full shadow-sm">{{ $unreadCount }} Baru</span>
                                @endif
                            </div>
                        </a>
                        
                        <div class="border-t border-gray-100 my-2"></div>
                        
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-rose-500 hover:bg-rose-50 rounded-xl">
                                <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar
                            </button>
                        </form>
                    </nav>
                </div>
            </header>
            
            <main class="flex-1 overflow-y-auto p-6 md:p-10 relative">
                @yield('content')
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
    
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false,
            customClass: {
                popup: 'rounded-2xl',
                title: 'text-gray-800 font-bold',
            }
        });
    </script>
    @endif
    
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            customClass: {
                popup: 'rounded-2xl'
            }
        });
    </script>
    @endif
    
    @stack('scripts')

    <!-- Semi-Realtime Polling (Notifications) -->
    <script>
        function checkNotifications() {
            fetch('{{ route('live.notifications') }}')
                .then(response => response.json())
                .then(data => {
                    // 1. Update Sidebar Notifications Badge
                    const sidebarNotifBadge = document.getElementById('live-sidebar-notifications-badge');
                    if (sidebarNotifBadge) {
                        if (data.count > 0) {
                            const countText = data.count > 9 ? '9+' : data.count;
                            sidebarNotifBadge.innerHTML = `<span class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[9px] font-bold text-white shadow-sm ring-2 ring-white">${countText}</span>`;
                        } else {
                            sidebarNotifBadge.innerHTML = '';
                        }
                    }

                    // 2. Update Sidebar Friends Badge
                    const sidebarFriendsBadge = document.getElementById('live-sidebar-friends-badge');
                    if (sidebarFriendsBadge) {
                        if (data.pending_friends_count > 0) {
                            sidebarFriendsBadge.innerHTML = `<span class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-orange-500 text-[9px] font-bold text-white shadow-sm ring-2 ring-white">${data.pending_friends_count}</span>`;
                        } else {
                            sidebarFriendsBadge.innerHTML = '';
                        }
                    }

                    // 3. Update Mobile Notifications Badge
                    const mobileNotifBadge = document.getElementById('live-mobile-notifications-badge');
                    if (mobileNotifBadge) {
                        if (data.count > 0) {
                            mobileNotifBadge.innerHTML = `<span class="bg-rose-500 text-white text-[10px] px-2 py-0.5 rounded-full shadow-sm">${data.count} Baru</span>`;
                        } else {
                            mobileNotifBadge.innerHTML = '';
                        }
                    }

                    // 4. Update Mobile Friends Badge
                    const mobileFriendsBadge = document.getElementById('live-mobile-friends-badge');
                    if (mobileFriendsBadge) {
                        if (data.pending_friends_count > 0) {
                            mobileFriendsBadge.innerHTML = `<span class="bg-orange-500 text-white text-[10px] px-2 py-0.5 rounded-full shadow-sm">${data.pending_friends_count} Baru</span>`;
                        } else {
                            mobileFriendsBadge.innerHTML = '';
                        }
                    }
                })
                .catch(error => console.error('Error fetching notifications:', error));
        }

        // Polling notifikasi setiap 7 detik
        setInterval(checkNotifications, 7000);
    </script>
</body>
</html>