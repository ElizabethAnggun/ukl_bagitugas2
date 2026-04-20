@extends('layouts.dashboard')

@section('title', 'Daftar Proyek')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Proyek</h1>
        <p class="text-gray-600 mt-1">Kelola semua proyek Anda</p>
    </div>
    <a href="{{ route('projects.create') }}" class="mt-4 sm:mt-0 gradient-bg text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition shadow-lg inline-flex items-center">
        <i class="fas fa-plus mr-2"></i>Tambah Proyek
    </a>
</div>

<!-- Projects Grid -->
@if($projects->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($projects as $project)
        <div class="card-hover bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Card Header -->
            <div class="p-6 border-b bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex items-start justify-between">
                    <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-folder text-white text-xl"></i>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('projects.edit', $project) }}" class="w-8 h-8 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center hover:bg-yellow-200 transition" title="Edit">
                            <i class="fas fa-edit text-sm"></i>
                        </a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-200 transition" title="Hapus">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mt-4">{{ $project->name }}</h3>
                <p class="text-gray-500 text-sm mt-1 line-clamp-2">{{ $project->description ?? 'Tidak ada deskripsi' }}</p>
            </div>
            
            <!-- Card Body -->
            <div class="p-6">
                <!-- Progress -->
                <div class="mb-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Progress</span>
                        <span class="font-semibold {{ $project->progress == 100 ? 'text-green-600' : 'text-indigo-600' }}">
                            {{ $project->progress }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full transition-all duration-500 {{ $project->progress == 100 ? 'bg-green-500' : 'bg-indigo-500' }}" 
                             style="width: {{ $project->progress }}%"></div>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-gray-800">{{ $project->tasks_count }}</p>
                        <p class="text-xs text-gray-500">Total Tugas</p>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-lg">
                        <p class="text-2xl font-bold text-green-600">{{ $project->completed_tasks }}</p>
                        <p class="text-xs text-green-600">Selesai</p>
                    </div>
                </div>
                
                <!-- Dates -->
                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                    <span>
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Mulai: {{ $project->start_date->format('d M Y') }}
                    </span>
                </div>
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <span>
                        <i class="fas fa-clock mr-1"></i>
                        Deadline: {{ $project->deadline->format('d M Y') }}
                    </span>
                </div>
            </div>
            
            <!-- Card Footer -->
            <div class="p-4 border-t bg-gray-50">
                <a href="{{ route('projects.show', $project) }}" class="block w-full text-center py-2 text-indigo-600 font-medium hover:bg-indigo-50 rounded-lg transition">
                    Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-folder-open text-gray-400 text-4xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Proyek</h3>
        <p class="text-gray-500 mb-6 max-w-md mx-auto">
            Mulai dengan membuat proyek pertama Anda. Kelola tugas dan pantau progress dengan mudah.
        </p>
        <a href="{{ route('projects.create') }}" class="gradient-bg text-white px-8 py-3 rounded-xl font-semibold hover:opacity-90 transition shadow-lg inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>Buat Proyek Pertama
        </a>
    </div>
@endif
@endsection
