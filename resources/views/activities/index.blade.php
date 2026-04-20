@extends('layouts.dashboard')

@section('title', 'Riwayat Aktivitas')

@section('content')
<!-- Header -->
<div class="mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Riwayat Aktivitas</h1>
    <p class="text-gray-600 mt-1">Lihat log aktivitas tugas Anda</p>
</div>

<!-- Activities Card -->
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b">
        <h2 class="text-lg font-bold text-gray-800">
            <i class="fas fa-history text-purple-500 mr-2"></i>Daftar Aktivitas
        </h2>
    </div>
    
    <div class="p-6">
        @if($activities->count() > 0)
            <div class="space-y-4">
                @foreach($activities as $activity)
                <div class="flex items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                    <!-- Icon -->
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-shrink-0
                        {{ $activity->status == 'selesai' ? 'bg-green-100' : 'bg-red-100' }}">
                        <span class="text-xl {{ $activity->status_color }}">{{ $activity->status_icon }}</span>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center flex-wrap gap-2">
                            <h3 class="font-semibold text-gray-800">{{ $activity->activity }}</h3>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $activity->status == 'selesai' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $activity->status }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-folder text-indigo-400 mr-1"></i>
                            {{ $activity->task->project->name }}
                        </p>
                        <p class="text-xs text-gray-400 mt-2">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $activity->created_at->format('d M Y H:i') }} 
                            ({{ $activity->created_at->diffForHumans() }})
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $activities->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-history text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Aktivitas</h3>
                <p class="text-gray-500 max-w-md mx-auto">
                    Aktivitas akan muncul ketika Anda menyelesaikan tugas atau ada tugas yang terlambat.
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Info Card -->
<div class="mt-8 bg-indigo-50 rounded-xl p-6">
    <div class="flex items-start">
        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
            <i class="fas fa-info-circle text-indigo-600 text-xl"></i>
        </div>
        <div>
            <h3 class="font-semibold text-indigo-900 mb-2">Tentang Riwayat Aktivitas</h3>
            <p class="text-indigo-700 text-sm">
                Riwayat aktivitas mencatat setiap perubahan status tugas menjadi "Selesai" atau "Terlambat". 
                Ini membantu Anda melacak perkembangan pekerjaan dan mengidentifikasi tugas-tugas yang memerlukan perhatian khusus.
            </p>
        </div>
    </div>
</div>
@endsection
