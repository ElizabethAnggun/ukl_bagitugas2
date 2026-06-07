@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<style>
    
</style>

<!-- Ambient Background Glow (Disamakan dengan Halaman Proyek) -->
<div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-400/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-cyan-400/20 rounded-full blur-[120px]"></div>
</div>

<!-- Welcome Section Banner Ultra-Modern -->
<!-- Welcome Section Banner (Fixed Layout: Kobi Anti-Tenggelam & Balon Pas) -->
<div class="mb-10 bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] rounded-[30px] px-6 pt-8 pb-0 md:px-10 md:py-10 shadow-xl flex flex-col md:flex-row relative overflow-hidden">

    <!-- Bagian Teks (Kiri/Atas) -->
    <div class="relative z-10 text-white w-full md:w-3/5 mb-6 md:mb-0">
        <span class="bg-white/20 backdrop-blur-sm px-4 py-1.5 rounded-full text-[10px] md:text-xs font-bold tracking-wider mb-4 inline-block border border-white/20">
            Selamat Datang di BagiTugas !
        </span>
        
        <h1 class="text-3xl md:text-4xl font-extrabold mb-3 drop-shadow-md leading-tight">
            Gimana kabarnya, <br class="md:hidden" /> {{ explode(' ', trim($user->name))[0] }}?
        </h1>
        <p class="text-white/90 text-sm md:text-lg font-medium leading-relaxed">
            Sudah siap untuk membagi tugas dan menyelesaikan proyek hari ini? Pantau deadline, kelola tim, dan gas kerjanya!
        </p>
    </div>
    
    <!-- Bagian Maskot Kobi (Kanan/Bawah) -->
    <div class="w-full md:w-2/5 flex justify-end items-end z-10 mt-2 md:mt-0">
        
        <div class="relative flex items-center mr-2 md:mr-0">
            
            <!-- Balon Teks -->
            <div class="absolute right-full mr-2 md:mr-3 top-8 md:top-12 bg-white px-5 py-2.5 rounded-2xl rounded-tr-none shadow-md text-[#1d61bd] font-extrabold text-xs md:text-sm animate-bounce z-20 whitespace-nowrap" style="animation-duration: 3s;">
                Hai, saya Kobi! 👋🏻✨
                
                <!-- Ekor Balon Chat Ala WhatsApp -->
                <svg class="absolute text-white w-3 h-4 -right-[11px] top-0" viewBox="0 0 12 16" fill="currentColor">
                    <path d="M0 0 L12 0 L0 16 Z" />
                </svg>
            </div>
            
            <!-- Gambar Maskot Kobi -->
            <img src="{{ asset('images/icon_ukl_v2.png') }}" 
                 alt="Kobi Mascot" 
                 class="w-28 md:w-44 drop-shadow-[0_15px_25px_rgba(0,0,0,0.3)] transition-transform duration-500 hover:scale-105 relative z-10 cursor-pointer">
        </div>
        
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 relative z-10">
    <div class="card-hover bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm p-6 border-l-4 border-[#1d61bd]">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Total Tugas</p>
                <p class="text-3xl font-bold text-gray-800 mt-2" id="live-total-tasks">{{ $totalTasks }}</p>
            </div>
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center">
                <i class="fas fa-clipboard-list text-[#1d61bd] text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="card-hover bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm p-6 border-l-4 border-[#0ea0d8]">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Berjalan</p>
                <p class="text-3xl font-bold text-gray-800 mt-2" id="live-running-tasks">{{ $runningTasks }}</p>
            </div>
            <div class="w-14 h-14 bg-sky-50 rounded-2xl flex items-center justify-center">
                <i class="fas fa-spinner text-[#0ea0d8] text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="card-hover bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm p-6 border-l-4 border-emerald-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Selesai</p>
                <p class="text-3xl font-bold text-gray-800 mt-2" id="live-completed-tasks">{{ $completedTasks }}</p>
            </div>
            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center">
                <i class="fas fa-check-circle text-emerald-500 text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="card-hover bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm p-6 border-l-4 border-rose-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Terlambat</p>
                <p class="text-3xl font-bold text-gray-800 mt-2" id="live-late-tasks">{{ $lateTasks }}</p>
            </div>
            <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center">
                <i class="fas fa-exclamation-circle text-rose-500 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-10">
    <div class="lg:col-span-2">
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-100 h-full">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-tasks text-[#1d61bd] mr-3 text-xl"></i>Tugas Anda
                </h2>
                <a href="{{ route('tasks.index') }}" class="text-sm font-semibold text-[#2F5CB4] hover:text-[#1e428a] transition">
                    Lihat Semua <i class="fas fa-chevron-right text-xs ml-1"></i>
                </a>
            </div>
            <div class="p-6" id="live-recent-tasks-container">
                @if($recentTasks->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentTasks as $task)
                        <div class="flex items-center justify-between p-4 bg-slate-50/80 hover:bg-slate-100/80 rounded-xl transition border border-slate-100">
                            <div class="flex-1 min-w-0 pr-4">
                                <h3 class="text-base font-bold text-gray-800 truncate">{{ $task->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1 truncate">{{ $task->project->name }}</p>
                            </div>
                            <div class="flex flex-col items-end whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                    {{ $task->status == 'belum_mulai' ? 'bg-gray-200 text-gray-700' : 
                                      ($task->status == 'berjalan' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                                    {{ str_replace('_', ' ', strtoupper($task->status)) }}
                                </span>
                                <span class="text-xs font-semibold text-gray-400 mt-2">
                                    <i class="fas fa-clock mr-1"></i>{{ $task->deadline->format('d M') }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-clipboard text-gray-300 text-3xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">Belum ada tugas yang ditugaskan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div>
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-100 h-full">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-history text-[#0ea0d8] mr-3 text-xl"></i>Aktivitas Terbaru
                </h2>
            </div>
            <div class="p-6">
                @if($recentActivities->count() > 0)
                    <div class="space-y-5">
                        @foreach($recentActivities as $activity)
                        <div class="flex items-start">
                            <div class="relative flex items-center justify-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center z-10 
                                    {{ $activity->status == 'selesai' ? 'bg-emerald-100 text-emerald-500' : 'bg-rose-100 text-rose-500' }}">
                                    <i class="fas {{ $activity->status == 'selesai' ? 'fa-check' : 'fa-exclamation' }}"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-800 leading-tight">
                                    Menyelesaikan tugas <span class="text-[#2F5CB4] font-bold">"{{ $activity->activity }}"</span>
                                </p>
                                <p class="text-xs text-gray-400 mt-1 font-medium">
                                    {{ $activity->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-history text-gray-300 text-3xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">Belum ada aktivitas</p>
                    </div>
                @endif
                <a href="{{ route('activities.index') }}" class="block w-full text-center py-3 mt-6 text-sm font-bold text-[#2F5CB4] bg-blue-50 hover:bg-blue-100 rounded-xl transition">
                    Lihat Semua Riwayat
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function updateDashboard() {
        fetch('{{ route('live.dashboard') }}')
            .then(response => response.json())
            .then(data => {
                // Update Stats
                document.getElementById('live-total-tasks').innerText = data.stats.total;
                document.getElementById('live-running-tasks').innerText = data.stats.running;
                document.getElementById('live-completed-tasks').innerText = data.stats.completed;
                document.getElementById('live-late-tasks').innerText = data.stats.late;

                // Update Recent Tasks
                const container = document.getElementById('live-recent-tasks-container');
                if (data.recent_tasks.length > 0) {
                    let html = '<div class="space-y-4">';
                    data.recent_tasks.forEach(task => {
                        html += `
                            <a href="${task.url}" class="flex items-center justify-between p-4 bg-slate-50/80 hover:bg-slate-100/80 rounded-xl transition border border-slate-100 group">
                                <div class="flex-1 min-w-0 pr-4">
                                    <h3 class="text-base font-bold text-gray-800 truncate group-hover:text-[#1d61bd] transition">${task.title}</h3>
                                    <p class="text-sm text-gray-500 mt-1 truncate">${task.project_name}</p>
                                </div>
                                <div class="flex flex-col items-end whitespace-nowrap">
                                    <span class="px-3 py-1 text-[10px] font-bold rounded-full ${task.status_color}">
                                        ${task.status_label}
                                    </span>
                                    <span class="text-[10px] font-semibold text-gray-400 mt-2">
                                        <i class="fas fa-clock mr-1 text-[9px]"></i>${task.created_at_human}
                                    </span>
                                </div>
                            </a>
                        `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = `
                        <div class="text-center py-10">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-clipboard-check text-gray-300 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada tugas baru</p>
                        </div>
                    `;
                }
            })
            .catch(error => console.error('Error fetching dashboard live data:', error));
    }

    // Update setiap 10 detik
    setInterval(updateDashboard, 10000);
</script>
@endpush