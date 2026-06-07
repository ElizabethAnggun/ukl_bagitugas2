@extends('layouts.dashboard')

@section('title', 'Tambah Proyek')

@section('content')
<!-- Header -->
<div class="mb-8">
    <a href="{{ route('projects.index') }}" class="text-gray-500 hover:text-gray-700 mb-4 inline-flex items-center">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Proyek
    </a>
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Tambah Proyek Baru</h1>
    <p class="text-gray-600 mt-1">Buat proyek baru untuk mengelola tugas-tugas Anda</p>
</div>

<!-- Form Card -->
<div class="bg-white rounded-xl shadow-sm max-w-2xl">
    <div class="p-6 border-b">
        <h2 class="text-lg font-bold text-gray-800">
            <i class="fas fa-folder-plus text-indigo-500 mr-2"></i>Informasi Proyek
        </h2>
    </div>
    
    <form action="{{ route('projects.store') }}" method="POST" class="p-6">
        @csrf
        
        <!-- Project Name -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-folder mr-2 text-indigo-500"></i>Nama Proyek <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   value="{{ old('name') }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                   placeholder="Contoh: Website E-Commerce"
                   required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-align-left mr-2 text-indigo-500"></i>Deskripsi
            </label>
            <textarea name="description" 
                      id="description" 
                      rows="4"
                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('description') border-red-500 @enderror"
                      placeholder="Jelaskan tentang proyek ini...">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Dates Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Start Date -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar mr-2 text-indigo-500"></i>Tanggal Mulai <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       name="start_date" 
                       id="start_date" 
                       value="{{ old('start_date', date('Y-m-d')) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('start_date') border-red-500 @enderror"
                       required>
                @error('start_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Deadline -->
            <div>
                <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-clock mr-2 text-indigo-500"></i>Deadline <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       name="deadline" 
                       id="deadline" 
                       value="{{ old('deadline') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('deadline') border-red-500 @enderror"
                       required>
                @error('deadline')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Buttons -->
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('projects.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" 
                class="px-8 py-2.5 bg-gradient-to-r from-[#1d61bd] to-[#0ea0d8] text-white font-bold rounded-xl shadow-md shadow-blue-200 hover:scale-[1.02] hover:shadow-lg transition-all flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan Proyek
            </button>
        </div>
    </form>
</div>
@endsection
