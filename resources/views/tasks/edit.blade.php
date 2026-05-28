@extends('layouts.dashboard')

@section('title', 'Edit Tugas')

@section('content')
<!-- Header -->
<div class="mb-8">
    <a href="{{ route('tasks.index') }}" class="text-gray-500 hover:text-gray-700 mb-4 inline-flex items-center">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Tugas
    </a>
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Edit Tugas</h1>
    <p class="text-gray-600 mt-1">Perbarui informasi tugas {{ $task->title }}</p>
</div>

<!-- Form Card -->
<div class="bg-white rounded-xl shadow-sm max-w-2xl">
    <div class="p-6 border-b">
        <h2 class="text-lg font-bold text-gray-800">
            <i class="fas fa-edit text-yellow-500 mr-2"></i>Edit Informasi Tugas
        </h2>
    </div>
    
    <form action="{{ route('tasks.update', $task) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        
        <!-- Task Title -->
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-heading mr-2 text-blue-500"></i>Judul Tugas <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   value="{{ old('title', $task->title) }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('title') border-red-500 @enderror"
                   placeholder="Contoh: Desain Logo Website"
                   required>
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Project -->
        <div class="mb-6">
            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-folder mr-2 text-indigo-500"></i>Proyek <span class="text-red-500">*</span>
            </label>
            <select name="project_id" 
                    id="project_id" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('project_id') border-red-500 @enderror"
                    required>
                @if($projects->count() > 1)
                    <option value="">Pilih Proyek</option>
                @endif
                
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" 
                        {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
            @error('project_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Assigned To -->
        <div class="mb-6">
            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-user mr-2 text-green-500"></i>Ditugaskan Ke <span class="text-red-500">*</span>
            </label>
            <select name="user_id" 
                    id="user_id" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('user_id') border-red-500 @enderror"
                    required>
                <option value="">Pilih User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $task->user_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Deadline -->
        <div class="mb-6">
            <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-calendar mr-2 text-red-500"></i>Deadline <span class="text-red-500">*</span>
            </label>
            <input type="date" 
                   name="deadline" 
                   id="deadline" 
                   value="{{ old('deadline', $task->deadline->format('Y-m-d')) }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('deadline') border-red-500 @enderror"
                   required>
            @error('deadline')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Status -->
        <div class="mb-6">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-tag mr-2 text-purple-500"></i>Status <span class="text-red-500">*</span>
            </label>
            <select name="status" 
                    id="status" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('status') border-red-500 @enderror"
                    required>
                <option value="belum_mulai" {{ old('status', $task->status) == 'belum_mulai' ? 'selected' : '' }}>Belum Mulai</option>
                <option value="berjalan" {{ old('status', $task->status) == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                <option value="selesai" {{ old('status', $task->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Buttons -->
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('tasks.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" class="bg-yellow-500 text-white px-6 py-3 rounded-xl font-medium hover:bg-yellow-600 transition shadow-lg">
                <i class="fas fa-save mr-2"></i>Perbarui Tugas
            </button>
        </div>
    </form>
</div>
@endsection
