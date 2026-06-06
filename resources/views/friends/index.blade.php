@extends('layouts.dashboard')

@section('title', 'Daftar Teman')

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

<!-- Ambient Background Glow -->
<div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-400/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-400/20 rounded-full blur-[120px]"></div>
</div>

<!-- Header Ultra-Modern -->
<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-10 relative z-10">
    <div>
        <h1 class="text-4xl md:text-4xl font-extrabold text-gray-900 tracking-tight pb-2 mb-1">
            Jejaring Teman
        </h1>
        <p class="text-gray-600 text-base font-medium">
            Lacak jejak penyelesaian tugas dan batas waktu yang terlewat.
        </p>
    </div>
    
    <button onclick="document.getElementById('addFriendModal').classList.remove('hidden')" class="mt-6 sm:mt-0 relative group inline-flex items-center justify-center">
        <!-- Efek Glow di belakang tombol -->
        <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-2xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
        <div class="relative bg-gradient-to-r from-[#1d61bd] to-[#0ea0d8] text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-xl hover:scale-[1.02] transition-all duration-300 flex items-center gap-3 cursor-pointer">
            <i class="fas fa-user-plus"></i> Tambah Teman
        </div>
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-10 items-start">
    
    <!-- List Teman (Kiri - Lebar) -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-lg shadow-blue-900/5 border border-white/80 overflow-hidden">
            <!-- PERBAIKAN: Padding diubah dari p-8 menjadi px-6 py-5 agar tidak terlalu lebar atas-bawahnya -->
            <div class="px-6 py-5 md:px-8 md:py-5 border-b border-gray-100/50 flex items-center justify-between bg-white/50">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#1d61bd] mr-4 shadow-sm">
                        <i class="fas fa-users"></i>
                    </div>
                    Teman Saya (<span id="live-friends-count">{{ $friends->count() }}</span>)
                </h2>
            </div>
            
            <!-- PERBAIKAN: Padding diubah dari p-6 md:p-8 menjadi px-6 py-5 md:px-8 md:py-6 -->
            <div class="px-6 py-5 md:px-8 md:py-6" id="live-friends-container">
                @if($friends->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5" id="live-friends-grid">
                        @foreach($friends as $friend)
                            <div class="group relative flex items-center p-5 bg-white border border-gray-100 rounded-2xl hover:border-blue-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1" id="friend-card-{{ $friend->id }}">
                                <!-- Avatar -->
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] flex items-center justify-center text-white font-extrabold text-xl mr-4 shadow-md group-hover:scale-105 transition-transform">
                                    {{ substr($friend->name, 0, 1) }}
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <p class="font-extrabold text-gray-900 truncate group-hover:text-[#1d61bd] transition-colors">{{ $friend->name }}</p>
                                    <p class="text-xs font-semibold text-gray-400 truncate mt-0.5">{{ $friend->email }}</p>
                                </div>
                                
                                <!-- Tombol Unfriend -->
                                <form action="{{ route('friends.unfriend', $friend) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pertemanan dengan {{ $friend->name }}?')" class="absolute right-4">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-rose-500 hover:text-white shadow-sm" title="Hapus Pertemanan">
                                        <i class="fas fa-user-minus"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- EMPTY STATE -->
                    <div class="text-center py-8">
                        <div class="w-32 h-32 mx-auto mb-4 relative">
                            <div class="absolute inset-0 bg-blue-100 rounded-full blur-2xl animate-pulse opacity-50"></div>
                            <img src="{{ asset('images/icon_ukl_v2.png') }}" alt="Empty Friends" class="relative z-10 w-24 h-24 object-contain mx-auto opacity-70 grayscale-[30%] animate-float-slow">
                        </div>
                        <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Belum Ada Teman</h3>
                        <p class="text-gray-500 text-sm max-w-sm mx-auto">Kirim permintaan pertemanan via email untuk mulai membagi tugas proyek bersama.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Panel Kanan (Permintaan) -->
    <div class="space-y-6">
        <!-- Permintaan Masuk -->
        <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-lg shadow-orange-900/5 border border-white/80 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100/50 flex items-center bg-white/50">
                <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 mr-3 shadow-sm">
                    <i class="fas fa-user-clock text-sm"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Permintaan Masuk</h2>
                <span id="live-pending-count-badge" class="{{ $pendingRequests->count() > 0 ? '' : 'hidden' }} ml-auto bg-orange-500 text-white text-[10px] font-bold px-2 py-1 rounded-full animate-pulse">{{ $pendingRequests->count() }}</span>
            </div>
            
            <div class="p-6" id="live-pending-container">
                @if($pendingRequests->count() > 0)
                    <div class="space-y-4" id="live-pending-list">
                        @foreach($pendingRequests as $request)
                            <div class="p-4 bg-orange-50/50 border border-orange-100 rounded-2xl hover:bg-orange-50 transition-colors" id="pending-request-{{ $request->id }}">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center text-white font-extrabold mr-3 shadow-sm">
                                        {{ substr($request->sender->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-extrabold text-gray-900 truncate">{{ $request->sender->name }}</p>
                                        <p class="text-[10px] font-semibold text-gray-500 truncate">{{ $request->sender->email }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <form action="{{ route('friends.accept', $request) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-emerald-100 text-emerald-700 py-2 rounded-xl text-xs font-extrabold hover:bg-emerald-500 hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-check mr-1"></i> Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('friends.reject', $request) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-rose-100 text-rose-700 py-2 rounded-xl text-xs font-extrabold hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-times mr-1"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-400 text-xs font-bold py-2">Tidak ada permintaan masuk.</p>
                @endif
            </div>
        </div>

        <!-- Menunggu Konfirmasi -->
        <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-lg shadow-blue-900/5 border border-white/80 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100/50 flex items-center bg-white/50">
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 mr-3 shadow-sm">
                    <i class="fas fa-paper-plane text-sm"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Menunggu Konfirmasi</h2>
            </div>
            
            <div class="p-6" id="live-sent-container">
                @if($sentRequests->count() > 0)
                    <div class="space-y-3" id="live-sent-list">
                        @foreach($sentRequests as $request)
                            <div class="flex items-center p-3 border border-gray-100 bg-gray-50/50 rounded-2xl" id="sent-request-{{ $request->id }}">
                                <div class="w-8 h-8 rounded-xl bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs mr-3">
                                    {{ substr($request->receiver->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-extrabold text-gray-800 truncate">{{ $request->receiver->name }}</p>
                                    <p class="text-[10px] font-semibold text-gray-400 truncate">{{ $request->receiver->email }}</p>
                                </div>
                                <span class="text-[9px] bg-blue-100 text-blue-600 font-extrabold px-2 py-1 rounded-md border border-blue-200 uppercase tracking-wider">
                                    Pending
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-400 text-xs font-bold py-2">Tidak ada permintaan keluar.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- MASKOT ASISTEN (KOALA NGINTIP) -->
<div class="fixed bottom-0 left-8 z-40 hidden lg:block group">
    <div class="absolute -top-14 left-1/2 -translate-x-1/2 bg-white px-5 py-3 rounded-2xl shadow-[0_5px_15px_rgba(0,0,0,0.1)] border border-blue-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none w-max bounce-bubble">
        <p class="text-sm font-extrabold text-[#1d61bd]">Yuk ajak temanmu gabung ke BagiTugas! 🐨</p>
        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white transform rotate-45 border-b border-r border-blue-50"></div>
    </div>
    <img src="{{ asset('images/icon_ukl_v2.png') }}" 
         alt="Helper Koala" 
         class="w-24 transform translate-y-12 group-hover:translate-y-2 transition-transform duration-500 ease-out drop-shadow-[0_10px_10px_rgba(0,0,0,0.2)]">
</div>

<!-- Add Friend Modal -->
<div id="addFriendModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900/40 backdrop-blur-md p-4 transition-all">
    <div class="bg-white/95 backdrop-blur-xl rounded-[2rem] w-full max-w-md p-8 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] border border-white relative overflow-hidden transform scale-100 transition-transform">
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-100 rounded-full blur-2xl opacity-50"></div>
        <div class="flex justify-between items-center mb-6 relative z-10">
            <h3 class="text-2xl font-extrabold text-gray-900 flex items-center">
                <i class="fas fa-user-plus text-[#1d61bd] mr-3"></i> Tambah Teman
            </h3>
            <button onclick="document.getElementById('addFriendModal').classList.add('hidden')" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 text-gray-500 rounded-full flex items-center justify-center transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('friends.request') }}" method="POST" class="relative z-10">
            @csrf
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-3 uppercase tracking-wider">Email Pengguna</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input type="email" name="email" class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/20 focus:border-[#1d61bd] transition-all text-sm font-semibold outline-none" placeholder="cth: vinz@gmail.com" required>
                </div>
                <p class="text-[11px] text-gray-500 mt-3 font-medium"><i class="fas fa-info-circle mr-1"></i> Permintaan akan dikirimkan untuk disetujui terlebih dahulu.</p>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('addFriendModal').classList.add('hidden')" class="px-6 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition-colors">
                    Batal
                </button>
                <button type="submit" class="bg-gradient-to-r from-[#1d61bd] to-[#0ea0d8] text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 hover:opacity-90 hover:scale-[1.02] transition-all">
                    Kirim Permintaan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function updateFriendsLive() {
        fetch('{{ route('live.friends_list') }}')
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                const csrfToken = data.csrf_token;

                // 1. Update List Teman Utama
                const friendsCountSpan = document.getElementById('live-friends-count');
                const friendsContainer = document.getElementById('live-friends-container');
                
                if (friendsCountSpan) friendsCountSpan.innerText = data.friends.length;

                if (data.friends.length > 0) {
                    let html = '<div class="grid grid-cols-1 sm:grid-cols-2 gap-5" id="live-friends-grid">';
                    data.friends.forEach(friend => {
                        html += `
                            <div class="group relative flex items-center p-5 bg-white border border-gray-100 rounded-2xl hover:border-blue-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1" id="friend-card-${friend.id}">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] flex items-center justify-center text-white font-extrabold text-xl mr-4 shadow-md group-hover:scale-105 transition-transform">
                                    ${friend.avatar}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-extrabold text-gray-900 truncate group-hover:text-[#1d61bd] transition-colors">${friend.name}</p>
                                    <p class="text-xs font-semibold text-gray-400 truncate mt-0.5">${friend.email}</p>
                                </div>
                                <form action="${friend.unfriend_url}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pertemanan dengan ${friend.name}?')" class="absolute right-4">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-rose-500 hover:text-white shadow-sm" title="Hapus Pertemanan">
                                        <i class="fas fa-user-minus"></i>
                                    </button>
                                </form>
                            </div>
                        `;
                    });
                    html += '</div>';
                    friendsContainer.innerHTML = html;
                } else {
                    friendsContainer.innerHTML = `
                        <div class="text-center py-8">
                            <div class="w-32 h-32 mx-auto mb-4 relative">
                                <div class="absolute inset-0 bg-blue-100 rounded-full blur-2xl animate-pulse opacity-50"></div>
                                <img src="{{ asset('images/icon_ukl_v2.png') }}" alt="Empty Friends" class="relative z-10 w-24 h-24 object-contain mx-auto opacity-70 grayscale-[30%] animate-float-slow">
                            </div>
                            <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Belum Ada Teman</h3>
                            <p class="text-gray-500 text-sm max-w-sm mx-auto">Kirim permintaan pertemanan via email untuk mulai membagi tugas proyek bersama.</p>
                        </div>
                    `;
                }

                // 2. Update Permintaan Masuk (Paling Penting)
                const pendingBadge = document.getElementById('live-pending-count-badge');
                const pendingContainer = document.getElementById('live-pending-container');
                
                if (data.pending_requests.length > 0) {
                    if (pendingBadge) {
                        pendingBadge.innerText = data.pending_requests.length;
                        pendingBadge.classList.remove('hidden');
                    }

                    let html = '<div class="space-y-4" id="live-pending-list">';
                    data.pending_requests.forEach(req => {
                        html += `
                            <div class="p-4 bg-orange-50/50 border border-orange-100 rounded-2xl hover:bg-orange-50 transition-colors" id="pending-request-${req.id}">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center text-white font-extrabold mr-3 shadow-sm">
                                        ${req.sender_avatar}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-extrabold text-gray-900 truncate">${req.sender_name}</p>
                                        <p class="text-[10px] font-semibold text-gray-500 truncate">${req.sender_email}</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <form action="${req.accept_url}" method="POST" class="flex-1">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <button type="submit" class="w-full bg-emerald-100 text-emerald-700 py-2 rounded-xl text-xs font-extrabold hover:bg-emerald-500 hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-check mr-1"></i> Terima
                                        </button>
                                    </form>
                                    <form action="${req.reject_url}" method="POST" class="flex-1">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <button type="submit" class="w-full bg-rose-100 text-rose-700 py-2 rounded-xl text-xs font-extrabold hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-times mr-1"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    pendingContainer.innerHTML = html;
                } else {
                    if (pendingBadge) pendingBadge.classList.add('hidden');
                    pendingContainer.innerHTML = '<p class="text-center text-gray-400 text-xs font-bold py-2">Tidak ada permintaan masuk.</p>';
                }

                // 3. Update Menunggu Konfirmasi
                const sentContainer = document.getElementById('live-sent-container');
                if (data.sent_requests.length > 0) {
                    let html = '<div class="space-y-3" id="live-sent-list">';
                    data.sent_requests.forEach(req => {
                        html += `
                            <div class="flex items-center p-3 border border-gray-100 bg-gray-50/50 rounded-2xl" id="sent-request-${req.id}">
                                <div class="w-8 h-8 rounded-xl bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs mr-3">
                                    ${req.receiver_avatar}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-extrabold text-gray-800 truncate">${req.receiver_name}</p>
                                    <p class="text-[10px] font-semibold text-gray-400 truncate">${req.receiver_email}</p>
                                </div>
                                <span class="text-[9px] bg-blue-100 text-blue-600 font-extrabold px-2 py-1 rounded-md border border-blue-200 uppercase tracking-wider">
                                    Pending
                                </span>
                            </div>
                        `;
                    });
                    html += '</div>';
                    sentContainer.innerHTML = html;
                } else {
                    sentContainer.innerHTML = '<p class="text-center text-gray-400 text-xs font-bold py-2">Tidak ada permintaan keluar.</p>';
                }
            })
            .catch(error => console.error('Error fetching friends live data:', error));
    }

    setInterval(updateFriendsLive, 5000);
</script>
@endpush
@endsection