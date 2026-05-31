@extends('layouts.dashboard')

@section('title', 'Daftar Tugas')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Tugas</h1>
        <p class="text-gray-600 mt-1">Kelola semua tugas Anda</p>
    </div>
    <a href="{{ route('tasks.create') }}" class="mt-4 sm:mt-0 gradient-bg text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition shadow-lg inline-flex items-center">
        <i class="fas fa-plus mr-2"></i>Tambah Tugas
    </a>
</div>

<!-- Filter & Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl p-4 shadow-sm text-center">
        <p class="text-2xl font-bold text-gray-800">{{ $tasks->count() }}</p>
        <p class="text-sm text-gray-500">Semua</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm text-center">
        <p class="text-2xl font-bold text-blue-600">{{ $tasks->where('status', 'berjalan')->count() }}</p>
        <p class="text-sm text-blue-600">Berjalan</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm text-center">
        <p class="text-2xl font-bold text-green-600">{{ $tasks->where('status', 'selesai')->count() }}</p>
        <p class="text-sm text-green-600">Selesai</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm text-center">
        <p class="text-2xl font-bold text-red-600">{{ $tasks->filter->isLate()->count() }}</p>
        <p class="text-sm text-red-600">Terlambat</p>
    </div>
</div>

<!-- Tasks Table -->
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b">
        <h2 class="text-lg font-bold text-gray-800">
            <i class="fas fa-list text-indigo-500 mr-2"></i>Daftar Tugas
        </h2>
    </div>
    
    <div class="p-6">
        @if($tasks->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Judul</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Proyek</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Ditugaskan Ke</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Deadline</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($tasks as $task)
                        <tr class="hover:bg-gray-50 transition" id="task-row-{{ $task->id }}">
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-800">{{ $task->title }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-sm">
                                    <i class="fas fa-folder mr-2"></i>
                                    {{ $task->project->name }}
                                </span>
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
                                <span class="text-gray-600 {{ $task->isLate() ? 'text-red-600 font-semibold' : '' }}">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $task->deadline->format('d M Y') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    @can('changeStatus', $task)
                                        <select onchange="updateStatus({{ $task->id }}, this.value)" 
                                                class="status-select px-3 py-1 rounded-full text-xs font-medium border-0 cursor-pointer {{ $task->status_color }}">
                                            <option value="belum_mulai" {{ $task->status == 'belum_mulai' ? 'selected' : '' }}>Belum Mulai</option>
                                            <option value="berjalan" {{ $task->status == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                            <option value="selesai" {{ $task->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $task->status_color }}">
                                            {{ $task->status_label }}
                                        </span>
                                    @endcan
                                    
                                    @if($task->isLate())
                                        <span class="text-[10px] text-red-500 font-bold mt-1 uppercase ml-2">
                                            <i class="fas fa-exclamation-circle mr-1"></i>Terlambat
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-2">
                                    <a href="{{ route('tasks.show', $task) }}" class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-200 transition" title="Detail & Diskusi">
                                        <i class="fas fa-comments text-sm"></i>
                                    </a>
                                    @can('update', $task)
                                        <a href="{{ route('tasks.edit', $task) }}" class="w-8 h-8 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center hover:bg-yellow-200 transition" title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                    @endcan

                                    @can('delete', $task)
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tugas ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-200 transition" title="Hapus">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    @endcan
                                    
                                    @if(!Auth::user()->can('update', $task) && !Auth::user()->can('delete', $task))
                                        <span class="text-xs text-gray-400 italic">Read Only</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-clipboard-list text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Tugas</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">
                    Mulai dengan membuat tugas pertama Anda untuk proyek yang sedang berjalan.
                </p>
                <a href="{{ route('tasks.create') }}" class="gradient-bg text-white px-8 py-3 rounded-xl font-semibold hover:opacity-90 transition shadow-lg inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>Tambah Tugas
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    /**
     * Update status tugas via AJAX
     * @param {number} taskId - ID tugas
     * @param {string} status - Status baru
     */
    function updateStatus(taskId, status) {
        const token = document.querySelector('meta[name="csrf-token"').getAttribute('content');
        
        fetch(`/tasks/${taskId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update class warna pada select
                const select = document.querySelector(`#task-row-${taskId} .status-select`);
                select.className = `status-select px-3 py-1 rounded-full text-xs font-medium border-0 cursor-pointer ${data.status_color}`;
                
                // Tampilkan notifikasi sukses
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Status tugas diperbarui',
                    timer: 1500,
                    showConfirmButton: false
                });
                
                // Refresh halaman setelah 1.5 detik untuk update statistik
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Gagal memperbarui status'
            });
        });
    }
</script>
@endpush
@endsection
