@extends('layouts.dashboard')

@section('title', $project->name)

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('projects.index') }}" class="text-gray-500 hover:text-gray-700 mb-4 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Proyek
            </a>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $project->name }}</h1>
        </div>
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <a href="{{ route('projects.edit', $project) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-xl font-medium hover:bg-yellow-600 transition inline-flex items-center">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini? Semua tugas akan ikut terhapus.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-xl font-medium hover:bg-red-600 transition inline-flex items-center">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Project Info Card -->
<div class="bg-white rounded-xl shadow-sm mb-8">
    <div class="p-6 border-b">
        <h2 class="text-lg font-bold text-gray-800">Detail Proyek</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Description -->
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Deskripsi</h3>
                <p class="text-gray-800">{{ $project->description ?? 'Tidak ada deskripsi' }}</p>
            </div>
            
            <!-- Dates -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Tanggal Mulai</h3>
                    <p class="text-gray-800">
                        <i class="fas fa-calendar text-indigo-500 mr-2"></i>
                        {{ $project->start_date->format('d M Y') }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Deadline</h3>
                    <p class="text-gray-800">
                        <i class="fas fa-clock text-red-500 mr-2"></i>
                        {{ $project->deadline->format('d M Y') }}
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Progress Section -->
        <div class="mt-6 pt-6 border-t">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-500">Progress Proyek</h3>
                <span class="text-lg font-bold {{ $project->progress == 100 ? 'text-green-600' : 'text-indigo-600' }}">
                    {{ $project->progress }}%
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="h-4 rounded-full transition-all duration-500 {{ $project->progress == 100 ? 'bg-green-500' : 'bg-indigo-500' }}" 
                     style="width: {{ $project->progress }}%"></div>
            </div>
            <div class="flex items-center justify-between mt-2 text-sm text-gray-500">
                <span>{{ $project->completed_tasks }} dari {{ $project->tasks_count }} tugas selesai</span>
                <span>{{ $project->tasks_count - $project->completed_tasks }} tugas tersisa</span>
            </div>
        </div>
    </div>
</div>

<!-- Tasks Section -->
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b flex items-center justify-between">
        <h2 class="text-lg font-bold text-gray-800">
            <i class="fas fa-clipboard-list text-blue-500 mr-2"></i>Daftar Tugas
        </h2>
        <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-600 transition inline-flex items-center text-sm">
            <i class="fas fa-plus mr-2"></i>Tambah Tugas
        </a>
    </div>
    
    <div class="p-6">
        @if($project->tasks->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Judul Tugas</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Ditugaskan Ke</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Deadline</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($project->tasks as $task)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-800">{{ $task->title }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 gradient-bg rounded-full flex items-center justify-center mr-2">
                                        <span class="text-white text-xs font-semibold">{{ substr($task->user->name, 0, 1) }}</span>
                                    </div>
                                    <span class="text-gray-700">{{ $task->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-600">{{ $task->deadline->format('d M Y') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $task->status_color }}">
                                    {{ $task->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-2">
                                    <a href="{{ route('tasks.edit', $task) }}" class="text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tugas ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clipboard text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500">Belum ada tugas dalam proyek ini</p>
                <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}" class="text-blue-600 hover:text-blue-700 font-medium mt-2 inline-block">
                    <i class="fas fa-plus mr-1"></i>Tambah Tugas Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
