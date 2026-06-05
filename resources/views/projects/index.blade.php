@extends('layouts.dashboard')

@section('title', 'Daftar Proyek')

@section('content')
<!-- Ambient Background Glow untuk keseluruhan halaman -->
<div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-400/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-cyan-400/20 rounded-full blur-[120px]"></div>
</div>

<!-- Header Ultra-Modern -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-12 relative z-10">
        <div class="mt-2 mb-10 relative z-10">
        <h1 class="text-4xl md:text-4xl font-extrabold text-gray-900 tracking-tight pb-2 mb-1">
            Proyek Anda
        </h1>
        <p class="text-gray-600 text-base font-medium">
            Pantau dan kelola kolaborasi tim dalam satu pantauan.
        </p>
    </div>
    
    <a href="{{ route('projects.create') }}" class="mt-6 sm:mt-0 relative group inline-flex items-center justify-center">
        <!-- Efek Glow di belakang tombol -->
        <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-2xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
        <button class="relative bg-gradient-to-r from-[#1d61bd] to-[#0ea0d8] text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-xl hover:scale-[1.02] transition-all duration-300 flex items-center gap-3">
            <i class="fas fa-plus"></i> Ciptakan Proyek
        </button>
    </a>
</div>

<!-- Bento Grid Layout -->
@if($projects->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 relative z-10">
        @foreach($projects as $project)
        <!-- Glassmorphism Card -->
        <div class="group relative bg-white/60 backdrop-blur-2xl rounded-[2rem] p-8 border border-white/80 shadow-[0_15px_40px_-15px_rgba(29,97,189,0.15)] hover:bg-white hover:shadow-[0_25px_50px_-12px_rgba(29,97,189,0.25)] hover:-translate-y-2 transition-all duration-500 overflow-hidden">
            
            <!-- Dekorasi Lingkaran Abstrak di Sudut Card -->
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-gradient-to-br from-blue-100/50 to-cyan-100/50 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>

            <!-- Top Row: Icon & Actions -->
            <div class="flex justify-between items-start mb-8 relative z-10">
                <div class="w-16 h-16 rounded-[1.25rem] bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-layer-group text-transparent bg-clip-text bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8]"></i>
                </div>
                
                <!-- Aksi (Edit/Delete) yang muncul halus saat dihover -->
                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0 transition-all duration-300">
                    <a href="{{ route('projects.edit', $project) }}" class="w-10 h-10 bg-white border border-gray-100 text-gray-500 hover:text-yellow-500 rounded-xl flex items-center justify-center shadow-sm transition-colors" title="Edit">
                        <i class="fas fa-pen text-sm"></i>
                    </a>
                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Proyek ini akan dihapus permanen. Lanjutkan?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-10 h-10 bg-white border border-gray-100 text-gray-500 hover:text-red-500 rounded-xl flex items-center justify-center shadow-sm transition-colors" title="Hapus">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Project Title & Desc -->
            <div class="relative z-10 mb-8">
                <h3 class="text-2xl font-extrabold text-gray-900 mb-2 truncate group-hover:text-[#1d61bd] transition-colors">{{ $project->name }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 h-10">{{ $project->description ?? 'Tidak ada deskripsi rinci untuk proyek ini.' }}</p>
            </div>
            
            <!-- Sleek Progress Bar -->
            <div class="mb-8 relative z-10 bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                <div class="flex justify-between items-end mb-3">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1">Penyelesaian</p>
                        <p class="text-sm font-bold text-gray-800">{{ $project->completed_tasks }} dari {{ $project->tasks_count }} Tugas</p>
                    </div>
                    <span class="text-xl font-extrabold {{ $project->progress == 100 ? 'text-emerald-500' : 'text-transparent bg-clip-text bg-gradient-to-r from-[#1d61bd] to-[#0ea0d8]' }}">
                        {{ $project->progress }}%
                    </span>
                </div>
                <!-- Track -->
                <div class="w-full bg-gray-200/80 rounded-full h-3.5 shadow-inner overflow-hidden">
                    <!-- Indicator -->
                    <div class="h-full rounded-full transition-all duration-1000 ease-out relative {{ $project->progress == 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-[#1d61bd] to-[#0ea0d8]' }}" 
                         style="width: {{ $project->progress }}%">
                        <!-- Shine effect on progress bar -->
                        <div class="absolute top-0 left-0 w-full h-full bg-white/20 animate-[shimmer_2s_infinite]"></div>
                    </div>
                </div>
            </div>
            
            <!-- Footer/Bottom Row -->
            <div class="flex items-center justify-between mt-auto relative z-10">
                <div class="flex flex-col">
                    <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1">Tenggat Waktu</span>
                    <span class="text-sm font-bold {{ $project->deadline->isPast() && $project->progress < 100 ? 'text-red-500' : 'text-gray-700' }}">
                        <i class="fas fa-flag-checkered mr-1"></i> {{ $project->deadline->format('d M Y') }}
                    </span>
                </div>
                
                <a href="{{ route('projects.show', $project) }}" class="group/btn flex items-center justify-center w-12 h-12 bg-gray-900 hover:bg-[#1d61bd] text-white rounded-2xl transition-all duration-300 shadow-lg hover:shadow-[#1d61bd]/30">
                    <i class="fas fa-arrow-right transform group-hover/btn:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
@else
    <!-- Empty State Ultra Modern -->
    <div class="relative w-full max-w-2xl mx-auto mt-12 bg-white/60 backdrop-blur-3xl rounded-[3rem] p-16 text-center border border-white shadow-[0_20px_60px_-15px_rgba(29,97,189,0.1)] overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNlNWU3ZWIiLz48L3N2Zz4=')] opacity-50 mask-image:radial-gradient(ellipse_at_center,black,transparent_80%)"></div>
        
        <div class="relative z-10">
            <div class="w-32 h-32 mx-auto mb-8 relative">
                <div class="absolute inset-0 bg-blue-100 rounded-full blur-2xl animate-pulse"></div>
                <img src="{{ asset('images/icon_ukl_v2.png') }}" alt="Koala" class="relative z-10 w-full h-full object-contain animate-float-slow">
            </div>
            <h3 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Ruang Kerja Kosong</h3>
            <p class="text-lg text-gray-500 mb-10 max-w-md mx-auto">Sistem belum mendeteksi adanya aktivitas proyek. Mulai inisiasi proyek pertamamu sekarang.</p>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-3 bg-gray-900 text-white px-10 py-5 rounded-2xl font-bold text-lg hover:bg-[#1d61bd] transition-all hover:scale-105 shadow-xl">
                <i class="fas fa-rocket text-yellow-400"></i> Mulai Proyek Baru
            </a>
        </div>
    </div>
@endif
@endsection