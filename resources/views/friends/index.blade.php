@extends('layouts.dashboard')

@section('title', 'Daftar Teman')

@section('content')
<!-- Header -->
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Daftar Teman</h1>
        <p class="text-gray-600 mt-1">Kelola pertemanan untuk penugasan tugas</p>
    </div>
    <button onclick="document.getElementById('addFriendModal').classList.remove('hidden')" 
            class="gradient-bg text-white px-6 py-2 rounded-xl font-medium hover:opacity-90 transition shadow-md">
        <i class="fas fa-user-plus mr-2"></i>Tambah Teman
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- List Teman -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2">
                <i class="fas fa-users text-indigo-500 mr-2"></i>Teman Saya ({{ $friends->count() }})
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($friends as $friend)
                    <div class="flex items-center p-4 border rounded-2xl hover:bg-gray-50 transition group">
                        <div class="w-12 h-12 rounded-full gradient-bg flex items-center justify-center text-white font-bold text-lg mr-4">
                            {{ substr($friend->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-800 truncate">{{ $friend->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $friend->email }}</p>
                        </div>
                        <form action="{{ route('friends.unfriend', $friend) }}" method="POST" onsubmit="return confirm('Hapus pertemanan?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-400 hover:text-red-600 transition opacity-0 group-hover:opacity-100">
                                <i class="fas fa-user-minus"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-400">Belum ada teman. Tambahkan teman melalui email untuk mulai berbagi tugas.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Permintaan Pertemanan -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2">
                <i class="fas fa-user-clock text-orange-500 mr-2"></i>Permintaan Masuk
            </h2>
            
            <div class="space-y-4">
                @forelse($pendingRequests as $request)
                    <div class="p-4 bg-orange-50 rounded-2xl">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-orange-500 font-bold mr-3">
                                {{ substr($request->sender->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-800 truncate">{{ $request->sender->name }}</p>
                                <p class="text-[10px] text-gray-500 truncate">{{ $request->sender->email }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <form action="{{ route('friends.accept', $request) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-green-500 text-white py-1.5 rounded-lg text-xs font-bold hover:bg-green-600 transition">
                                    Terima
                                </button>
                            </form>
                            <form action="{{ route('friends.reject', $request) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-white text-red-500 border border-red-200 py-1.5 rounded-lg text-xs font-bold hover:bg-red-50 transition">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 text-sm">Tidak ada permintaan baru.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2">
                <i class="fas fa-paper-plane text-blue-500 mr-2"></i>Menunggu Konfirmasi
            </h2>
            
            <div class="space-y-4">
                @forelse($sentRequests as $request)
                    <div class="flex items-center p-3 border border-dashed rounded-2xl">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-xs mr-3">
                            {{ substr($request->receiver->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-800 truncate">{{ $request->receiver->name }}</p>
                            <p class="text-[10px] text-gray-400 truncate">{{ $request->receiver->email }}</p>
                        </div>
                        <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Pending</span>
                    </div>
                @empty
                    <p class="text-center text-gray-400 text-sm">Tidak ada permintaan keluar.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Add Friend Modal -->
<div id="addFriendModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Tambah Teman Baru</h3>
            <button onclick="document.getElementById('addFriendModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('friends.request') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email User</label>
                <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="Masukkan email terdaftar..." required>
                <p class="text-[10px] text-gray-400 mt-2 italic">Permintaan akan dikirimkan ke user tersebut untuk disetujui.</p>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('addFriendModal').classList.add('hidden')" class="px-6 py-2 text-gray-600 font-semibold">Batal</button>
                <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-xl font-semibold shadow-md hover:opacity-90 transition">Kirim Permintaan</button>
            </div>
        </form>
    </div>
</div>
@endsection
