@extends('layouts.dashboard')

@section('title', 'Notifikasi')

@section('content')
<style>
    .animate-float-slow {
        animation: float-slow 5s ease-in-out infinite;
    }
    @keyframes float-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .bounce-bubble {
        animation: bounce-bubble 2s infinite ease-in-out;
    }
    @keyframes bounce-bubble {
        0%, 100% { transform: translate(-50%, 0); }
        50% { transform: translate(-50%, -5px); }
    }
</style>

<!-- Ambient Background -->
<div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-400/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-[#0ea0d8]/20 rounded-full blur-[120px]"></div>
</div>

<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-10 relative z-10">
    <div>
        <h1 class="text-4xl md:text-4xl font-extrabold text-gray-900 tracking-tight pb-2 mb-1">
            Pusat Notifikasi
        </h1>
        <p class="text-gray-500 text-base font-medium">
        Jangan lewatkan pembaruan penting dari tim dan proyek Anda.
        </p>
    </div>
    
    @if($notifications->where('is_read', false)->count() > 0)
        <form action="{{ route('notifications.markAllRead') }}" method="POST" class="mt-6 sm:mt-0 relative group inline-flex items-center justify-center">
            @csrf
            <div class="absolute -inset-0.5 bg-gradient-to-r from-[#1d61bd] to-[#0ea0d8] rounded-2xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
            <button type="submit" class="relative bg-white text-[#1d61bd] px-6 py-3.5 rounded-2xl font-bold text-sm shadow-xl hover:bg-blue-50 hover:scale-[1.02] transition-all duration-300 flex items-center gap-2 border border-blue-100">
                <i class="fas fa-check-double text-[#0ea0d8]"></i> Tandai Semua Dibaca
            </button>
        </form>
    @endif
</div>

<div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-lg shadow-blue-900/5 border border-white/80 relative z-10 overflow-hidden mb-8">
    <div class="px-6 py-5 md:px-8 border-b border-gray-100/50 flex items-center justify-between bg-white/50">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] flex items-center justify-center text-white mr-4 shadow-sm">
                <i class="fas fa-bell"></i>
            </div>
            Pemberitahuan Terbaru
        </h2>
        @if($notifications->where('is_read', false)->count() > 0)
            <span class="bg-[#1d61bd] text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm animate-pulse">
                {{ $notifications->where('is_read', false)->count() }} Baru
            </span>
        @endif
    </div>
    
    <div>
        @if($notifications->count() > 0)
            <div class="divide-y divide-gray-100/50">
                @foreach($notifications as $notification)
                <a href="{{ route('notifications.read', $notification) }}" 
                   class="block relative group transition-all duration-300 hover:bg-slate-50/80 
                          {{ !$notification->is_read ? 'bg-blue-50/40 border-l-4 border-[#1d61bd]' : 'bg-transparent border-l-4 border-transparent hover:border-blue-200' }}">
                    
                    <div class="px-6 py-5 md:px-8 md:py-6 flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm transition-transform group-hover:scale-110 
                            {{ !$notification->is_read ? 'bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] text-white' : 'bg-gray-100 text-gray-400' }}">
                            <i class="fas {{ !$notification->is_read ? 'fa-bell' : 'fa-check' }}"></i>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-1">
                                <h3 class="text-base font-extrabold truncate pr-4 {{ !$notification->is_read ? 'text-gray-900 group-hover:text-[#1d61bd]' : 'text-gray-600' }}">
                                    {{ $notification->title ?? 'Pembaruan Sistem' }}
                                </h3>
                                <span class="text-[11px] font-bold uppercase tracking-wider mt-1 sm:mt-0 {{ !$notification->is_read ? 'text-[#0ea0d8]' : 'text-gray-400' }}">
                                    <i class="fas fa-clock mr-1 opacity-70"></i>{{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm leading-relaxed {{ !$notification->is_read ? 'text-gray-700 font-medium' : 'text-gray-500' }}">
                                {{ $notification->message }}
                            </p>
                        </div>
                        
                        @if(!$notification->is_read)
                            <div class="w-3 h-3 bg-[#1d61bd] rounded-full shadow-[0_0_8px_rgba(29,97,189,0.6)] mt-2 flex-shrink-0"></div>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
            
            @if($notifications->hasPages())
                <div class="px-6 py-5 md:px-8 border-t border-gray-100/50 bg-white/30">
                    {{ $notifications->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <div class="w-32 h-32 mx-auto mb-6 relative">
                    <div class="absolute inset-0 bg-[#0ea0d8]/20 rounded-full blur-2xl animate-pulse opacity-50"></div>
                    <img src="{{ asset('images/icon_ukl_v2.png') }}" alt="Empty Notifications" class="relative z-10 w-24 h-24 object-contain mx-auto opacity-70 grayscale-[30%] animate-float-slow">
                </div>
                <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Hening...</h3>
                <p class="text-gray-500 text-sm max-w-md mx-auto">
                    Belum ada pemberitahuan baru untuk Anda. Silakan bersantai atau cek tugas yang sedang berjalan!
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Koala Maskot -->
<div class="fixed bottom-0 left-8 z-40 hidden lg:block group">
    <div class="absolute -top-14 left-1/2 -translate-x-1/2 bg-white px-5 py-3 rounded-2xl shadow-[0_5px_15px_rgba(0,0,0,0.1)] border border-blue-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none w-max bounce-bubble">
        <p class="text-sm font-extrabold text-[#1d61bd]">Aman kak {{ explode(' ', Auth::user()->name)[0] }}, belum ada info penting! 🐨</p>
        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white transform rotate-45 border-b border-r border-blue-50"></div>
    </div>
    
    <img src="{{ asset('images/icon_ukl_v2.png') }}" 
         alt="Helper Koala" 
         class="w-24 transform translate-y-12 group-hover:translate-y-2 transition-transform duration-500 ease-out drop-shadow-[0_10px_10px_rgba(0,0,0,0.2)]">
</div>
@endsection