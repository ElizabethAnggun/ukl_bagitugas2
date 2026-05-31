@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<style>
    .animate-float-slow {
        animation: float-slow 5s ease-in-out infinite;
    }
    @keyframes float-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>

<!-- Ambient Background Glow (Disamakan dengan Halaman Proyek) -->
<div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-400/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-cyan-400/20 rounded-full blur-[120px]"></div>
</div>

<!-- Welcome Section Banner -->
<div class="mb-10 bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] rounded-[30px] px-6 py-6 md:px-10 md:py-6 shadow-2xl flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
    <div class="relative z-10 text-white w-full md:w-2/3">
        <h1 class="text-2xl md:text-3xl font-extrabold mb-2 drop-shadow-md">
            Selamat datang, {{ $user->name }}! 👋
        </h1>
        <p class="text-white/90 text-base md:text-lg font-medium">
            Siap untuk membagi tugas dan menyelesaikan proyek hari ini? Klik tugasnya, gas kerjanya!
        </p>
    </div>
    <div class="hidden md:flex w-1/3 relative z-10 justify-end">
        <img src="{{ asset('images/icon_ukl_v2.png') }}" 
             alt="Koala Mascot" 
             class="w-32 md:w-44 drop-shadow-[0_15px_25px_rgba(0,0,0,0.3)] animate-float-slow">
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 relative z-10">
    <div class="card-hover bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm p-6 border-l-4 border-[#1d61bd]">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Total Tugas</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalTasks }}</p>
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
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $runningTasks }}</p>
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
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $completedTasks }}</p>
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
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $lateTasks }}</p>
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
            <div class="p-6">
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

<div class="fixed bottom-8 right-8 z-50">
    <a href="{{ route('tasks.create') }}" 
       class="w-16 h-16 bg-[#2F5CB4] hover:bg-[#1e428a] text-white rounded-full shadow-[0_10px_20px_rgba(47,92,180,0.4)] flex items-center justify-center transition-all hover:scale-110 hover:-translate-y-1 group" 
       title="Tambah Tugas">
        <i class="fas fa-plus text-2xl group-hover:rotate-90 transition-transform duration-300"></i>
    </a>
</div>
@endsection