@extends('layouts.dashboard')

@section('title', 'Notifikasi')

@section('content')
<!-- Header -->
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Notifikasi</h1>
        <p class="text-gray-600 mt-1">Lihat semua pemberitahuan untuk Anda</p>
    </div>
    @if($notifications->where('is_read', false)->count() > 0)
        <form action="{{ route('notifications.markAllRead') }}" method="POST">
            @csrf
            <button type="submit" class="bg-indigo-100 text-indigo-700 px-6 py-2 rounded-xl font-medium hover:bg-indigo-200 transition">
                <i class="fas fa-check-double mr-2"></i>Tandai Semua Dibaca
            </button>
        </form>
    @endif
</div>

<!-- Notifications Card -->
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="p-6">
        @if($notifications->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($notifications as $notification)
                <a href="{{ route('notifications.read', $notification) }}" 
                   class="block p-4 hover:bg-gray-50 transition relative {{ !$notification->is_read ? 'bg-indigo-50/30' : '' }}">
                    <div class="flex items-start gap-4">
                        <!-- Icon -->
                        <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center 
                            {{ !$notification->is_read ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-400' }}">
                            <i class="fas fa-bell"></i>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="font-bold text-gray-800 {{ !$notification->is_read ? '' : 'text-gray-600 font-medium' }}">
                                    {{ $notification->title }}
                                </h3>
                                <span class="text-xs text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $notification->message }}</p>
                        </div>
                        
                        <!-- Status Dot -->
                        @if(!$notification->is_read)
                            <div class="w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-6 p-4 border-t">
                {{ $notifications->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-bell-slash text-gray-300 text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Ada Notifikasi</h3>
                <p class="text-gray-500 max-w-md mx-auto">
                    Pemberitahuan akan muncul di sini ketika ada aktivitas baru yang melibatkan Anda.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
