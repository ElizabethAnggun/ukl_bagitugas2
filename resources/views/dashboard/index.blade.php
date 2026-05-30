@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
        Selamat datang, <span class="text-indigo-600">{{ $user->name }}</span>! 👋
    </h1>
    <p class="text-gray-600 mt-1">Berikut ringkasan aktivitas Anda hari ini.</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total My Tasks -->
    <div class="card-hover bg-white rounded-xl shadow-sm p-6 border-l-4 border-indigo-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Tugas Saya</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalTasks }}</p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-clipboard-list text-indigo-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Running Tasks -->
    <div class="card-hover bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Tugas Berjalan</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $runningTasks }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-spinner text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Completed Tasks -->
    <div class="card-hover bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Tugas Selesai</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $completedTasks }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Late Tasks -->
    <div class="card-hover bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Tugas Terlambat</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $lateTasks }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Projects Section -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-folder text-indigo-500 mr-2"></i>Proyek yang Saya Ikuti
                </h2>
                <a href="{{ route('projects.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="p-6">
                @if($projects->count() > 0)
                    <div class="space-y-4">
                        @foreach($projects as $project)
                        <div class="border rounded-xl p-4 hover:shadow-md transition">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-gray-800">{{ $project->name }}</h3>
                                <div class="flex items-center gap-3">
                                    @if($project->user_id === Auth::id())
                                        <span class="text-[10px] bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded-full font-bold uppercase">Owner</span>
                                    @endif
                                    <span class="text-sm text-gray-500">
                                        {{ $project->tasks_count }} tugas
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="mb-2">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Progress Proyek</span>
                                    <span class="font-semibold {{ $project->progress == 100 ? 'text-green-600' : 'text-indigo-600' }}">
                                        {{ $project->progress }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full transition-all duration-500 {{ $project->progress == 100 ? 'bg-green-500' : 'bg-indigo-500' }}" 
                                         style="width: {{ $project->progress }}%"></div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Deadline: {{ $project->deadline->format('d M Y') }}
                                </span>
                                <a href="{{ route('projects.show', $project) }}" class="text-indigo-600 hover:text-indigo-700">
                                    Detail <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-folder-open text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">Belum ada proyek</p>
                        <a href="{{ route('projects.create') }}" class="text-indigo-600 hover:text-indigo-700 font-medium mt-2 inline-block">
                            <i class="fas fa-plus mr-1"></i>Buat Proyek Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-8">
        <!-- Recent Tasks -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-clipboard-list text-blue-500 mr-2"></i>Tugas Saya Terbaru
                </h2>
            </div>
            <div class="p-6">
                @if($recentTasks->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentTasks as $task)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-2 h-2 rounded-full mr-3 
                                {{ $task->status == 'selesai' ? 'bg-green-500' : '' }}
                                {{ $task->isLate() && $task->status != 'selesai' ? 'bg-red-500' : '' }}
                                {{ $task->status == 'berjalan' ? 'bg-blue-500' : '' }}
                                {{ $task->status == 'belum_mulai' ? 'bg-gray-400' : '' }}">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $task->title }}</p>
                                <p class="text-xs text-gray-500">{{ $task->project->name }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full {{ $task->status_color }}">
                                {{ $task->status_label }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada tugas</p>
                @endif
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-history text-orange-500 mr-2"></i>Riwayat Aktivitas
                </h2>
            </div>
            <div class="p-6">
                @if($recentActivities->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center mr-3 mt-0.5 border">
                                <span class="text-xs font-bold {{ $activity->status_color }}">
                                    {!! $activity->status_icon !!}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-800">
                                    Menyelesaikan tugas <span class="text-indigo-600">"{{ $activity->activity }}"</span>
                                </p>
                                <p class="text-[10px] text-gray-500 mt-0.5">
                                    {{ $activity->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <i class="fas fa-tasks text-gray-300 text-3xl mb-2"></i>
                        <p class="text-gray-500 text-xs">Belum ada aktivitas</p>
                    </div>
                @endif
                <a href="{{ route('activities.index') }}" class="block text-center text-xs font-bold text-indigo-600 hover:text-indigo-700 mt-6 pt-4 border-t">
                    Lihat Semua Riwayat
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="fixed bottom-6 right-6 flex flex-col space-y-3">
    <a href="{{ route('tasks.create') }}" class="w-14 h-14 bg-blue-500 hover:bg-blue-600 text-white rounded-full shadow-lg flex items-center justify-center transition" title="Tambah Tugas">
        <i class="fas fa-plus text-xl"></i>
    </a>

</div>
@endsection
