@extends('layouts.dashboard')

@section('title', 'Riwayat Aktivitas')

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
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-400/20 rounded-full blur-[120px]"></div>
</div>

<!-- Header Ultra-Modern -->
<div class="mb-10 relative z-10">
    <h1 class="text-4xl md:text-4xl font-extrabold text-gray-900 tracking-tight pb-2 mb-1">
        Riwayat Aktivitas
    </h1>
    <p class="text-gray-500 text-lg font-medium">Lacak jejak penyelesaian tugas dan batas waktu yang terlewat.</p>
</div>

<!-- Activities Container (Glassmorphism) -->
<div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-lg shadow-blue-900/5 border border-white/80 relative z-10 overflow-hidden mb-8">
    <div class="p-8 border-b border-gray-100/50 flex items-center justify-between bg-white/50">
        <h2 class="text-xl font-bold text-gray-800 flex items-center">
            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 mr-4 shadow-sm">
                <i class="fas fa-history"></i>
            </div>
            Daftar Aktivitas
        </h2>
    </div>
    
    <div class="p-6 md:p-8">
        @if($activities->count() > 0)
            <div class="space-y-4">
                @foreach($activities as $activity)
                <div class="group flex items-start p-5 bg-white hover:bg-slate-50/80 rounded-2xl transition-all duration-300 border border-gray-100 hover:border-blue-100 shadow-sm hover:shadow-md">
                    <!-- Icon dengan Efek Hover -->
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mr-5 flex-shrink-0 shadow-sm group-hover:scale-110 transition-transform duration-300
                        {{ $activity->status == 'selesai' ? 'bg-gradient-to-br from-emerald-100 to-emerald-200' : 'bg-gradient-to-br from-rose-100 to-rose-200' }}">
                        <span class="text-2xl {{ $activity->status_color }}">{{ $activity->status_icon }}</span>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-1">
                            <h3 class="text-base font-extrabold text-gray-900 group-hover:text-blue-700 transition-colors">{{ $activity->activity }}</h3>
                            <span class="inline-flex px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider shadow-sm w-max
                                {{ $activity->status == 'selesai' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                {{ $activity->status }}
                            </span>
                        </div>
                        <p class="text-sm font-semibold text-gray-500 mt-2 flex items-center">
                            <i class="fas fa-folder text-indigo-400 mr-2 opacity-70"></i>
                            {{ $activity->task->project->name }}
                        </p>
                        <div class="flex items-center gap-4 mt-3">
                            <p class="text-xs font-bold text-gray-400 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                                <i class="fas fa-clock mr-1.5 opacity-70"></i>
                                {{ $activity->created_at->format('d M Y, H:i') }}
                            </p>
                            <p class="text-xs font-bold text-blue-400 italic">
                                {{ $activity->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination Styling -->
            <div class="mt-8 pt-4">
                {{ $activities->links() }}
            </div>
        @else
            <!-- EMPTY STATE: Si Koala Gabut -->
            <div class="relative w-full max-w-2xl mx-auto my-8 bg-gray-50/50 rounded-[3rem] p-16 text-center border border-dashed border-gray-200 overflow-hidden">
                <div class="relative z-10">
                    <div class="w-32 h-32 mx-auto mb-6 relative">
                        <div class="absolute inset-0 bg-purple-100 rounded-full blur-2xl animate-pulse"></div>
                        <img src="{{ asset('images/icon_ukl_v2.png') }}" alt="Empty History" class="relative z-10 w-24 h-24 object-contain mx-auto opacity-60 grayscale-[50%] animate-float-slow">
                    </div>
                    <h3 class="text-3xl font-extrabold text-gray-900 mb-3">Ruang Riwayat Sepi</h3>
                    <p class="text-lg text-gray-500 max-w-md mx-auto">Sistem belum mencatat adanya tugas yang diselesaikan atau terlewat. Ayo mulai eksekusi tugasmu sekarang!</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Info Card Ultra-Modern -->
<div class="mt-8 bg-gradient-to-r from-indigo-50 to-blue-50/50 backdrop-blur-sm rounded-[2rem] p-8 border border-indigo-100/50 relative z-10 flex flex-col md:flex-row items-start hover:shadow-lg transition-shadow duration-300">
    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mr-6 flex-shrink-0 shadow-md text-indigo-600 mb-4 md:mb-0">
        <i class="fas fa-info-circle text-3xl"></i>
    </div>
    <div>
        <h3 class="text-lg font-extrabold text-indigo-900 mb-2">Pusat Rekam Jejak Sistem</h3>
        <p class="text-indigo-700 font-medium leading-relaxed">
            Halaman ini bertindak sebagai buku log otomatis. Setiap perubahan krusial, seperti saat kamu atau tim menyelesaikan tugas, atau saat ada tugas yang melewati tenggat waktu, akan tercatat permanen di sini. Gunakan riwayat ini untuk bahan evaluasi performa kerjamu.
        </p>
    </div>
</div>

<!-- MASKOT ASISTEN (KOALA NGINTIP) -->
<div class="fixed bottom-0 left-8 z-40 hidden lg:block group">
    <!-- Balon Kata -->
    <div class="absolute -top-14 left-1/2 -translate-x-1/2 bg-white px-5 py-3 rounded-2xl shadow-[0_5px_15px_rgba(0,0,0,0.1)] border border-purple-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none w-max bounce-bubble">
        <p class="text-sm font-extrabold text-purple-600">Semoga banyak yang hijau (selesai) ya! 🐨</p>
        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white transform rotate-45 border-b border-r border-purple-50"></div>
    </div>
    
    <!-- Maskotnya -->
    <img src="{{ asset('images/icon_ukl_v2.png') }}" 
         alt="Helper Koala" 
         class="w-24 transform translate-y-12 group-hover:translate-y-2 transition-transform duration-500 ease-out drop-shadow-[0_10px_10px_rgba(0,0,0,0.2)]">
</div>
@endsection