@extends('layouts.dashboard')

@section('title', 'Detail Proyek')

@section('content')
<!-- Header -->
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <a href="{{ route('projects.index') }}" class="text-gray-500 hover:text-gray-700 mb-2 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Proyek
        </a>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $project->name }}</h1>
        <p class="text-gray-600 mt-1">Dikelola oleh: <span class="font-semibold text-indigo-600">{{ $project->user->name }}</span></p>
    </div>
    
    @if($isOwner)
    <div class="flex space-x-3">
        <a href="{{ route('projects.edit', $project) }}" class="bg-yellow-500 text-white px-6 py-2 rounded-xl font-medium hover:bg-yellow-600 transition shadow-sm">
            <i class="fas fa-edit mr-2"></i>Edit Proyek
        </a>
    </div>
    @endif
</div>

@if(($isOwner || $project->managers->contains(Auth::id())) && $stats)
<!-- Statistics Cards (Untuk Owner & Sub Pengelola) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-4 border-l-4 border-indigo-500">
        <p class="text-gray-500 text-xs font-bold uppercase">Total Tugas</p>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-4 border-l-4 border-gray-400">
        <p class="text-gray-500 text-xs font-bold uppercase">Belum Mulai</p>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['belum_mulai'] }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-4 border-l-4 border-blue-500">
        <p class="text-gray-500 text-xs font-bold uppercase">Berjalan</p>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['berjalan'] }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-4 border-l-4 border-green-500">
        <p class="text-gray-500 text-xs font-bold uppercase">Selesai</p>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['selesai'] }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-4 border-l-4 border-red-500">
        <p class="text-gray-500 text-xs font-bold uppercase">Terlambat</p>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['terlambat'] }}</p>
    </div>
</div>

<!-- Project Progress (Untuk Owner & Sub Pengelola) -->
<div class="bg-white rounded-2xl shadow-sm p-6 mb-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold text-gray-800">Progress Proyek Keseluruhan</h2>
        <span class="text-2xl font-bold text-indigo-600" id="live-progress-text">{{ $stats['progress'] }}%</span>
    </div>
    <div class="w-full bg-gray-100 rounded-full h-4">
        <div class="h-4 rounded-full transition-all duration-1000 {{ $stats['progress'] == 100 ? 'bg-green-500' : 'gradient-bg' }}" 
             id="live-progress-bar" style="width: {{ $stats['progress'] }}%"></div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Task List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-tasks text-blue-500 mr-2"></i>
                    {{ $isOwner || $project->managers->contains(Auth::id()) ? 'Semua Tugas Proyek' : 'Tugas Saya di Proyek Ini' }}
                </h2>
                @if($isOwner || $project->managers->contains(Auth::id()))
                <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-bold">
                    <i class="fas fa-plus mr-1"></i>Tambah Tugas
                </a>
                @endif
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider font-bold">
                            <th class="px-6 py-4">Tugas</th>
                            <th class="px-6 py-4">Ditugaskan</th>
                            <th class="px-6 py-4">Deadline</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="live-task-table-body">
                        @forelse($tasks as $task)
                        <tr class="hover:bg-gray-50 transition" id="task-row-{{ $task->id }}">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-800">{{ $task->title }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-7 h-7 rounded-full gradient-bg flex items-center justify-center text-white text-[10px] font-bold mr-2">
                                        {{ substr($task->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs text-gray-600">{{ $task->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-500">
                                    {{ $task->deadline->format('d M Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col" id="task-status-{{ $task->id }}">
                                    @can('changeStatus', $task)
                                        <select onchange="updateTaskStatus({{ $task->id }}, this.value)" 
                                                class="status-select px-3 py-1 rounded-full text-[10px] font-bold border-0 cursor-pointer {{ $task->status_color }}">
                                            <option value="belum_mulai" {{ $task->status == 'belum_mulai' ? 'selected' : '' }}>Belum Mulai</option>
                                            <option value="berjalan" {{ $task->status == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                            <option value="selesai" {{ $task->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold w-fit {{ $task->status_color }}">
                                            {{ $task->status_label }}
                                        </span>
                                    @endcan
                                    @if($task->isLate())
                                        <span class="text-[9px] text-red-500 font-bold mt-1 uppercase">Terlambat</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('tasks.show', $task) }}" class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition" title="Diskusi">
                                        <i class="fas fa-comments text-xs"></i>
                                    </a>
                                    @can('update', $task)
                                        <a href="{{ route('tasks.edit', $task) }}" class="w-8 h-8 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center hover:bg-yellow-100 transition">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $task)
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tugas ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 bg-red-50 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-100 transition" title="Hapus">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 italic text-sm">
                                Belum ada tugas yang ditugaskan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Project Info Sidebar -->
    <div class="space-y-6">
        <!-- Sub Pengelola Section -->
        @if($isOwner)
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Sub Pengelola</h2>
            
            <!-- List Managers -->
            @if($project->managers->count() > 0)
                <div class="space-y-3 mb-6">
                    @foreach($project->managers as $manager)
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full gradient-bg flex items-center justify-center text-white text-xs font-bold mr-2">
                                {{ substr($manager->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $manager->name }}</p>
                                <p class="text-[10px] text-gray-500">{{ $manager->email }}</p>
                            </div>
                        </div>
                        <form action="{{ route('projects.removeManager', [$project, $manager]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Hapus Sub Pengelola">
                                <i class="fas fa-times-circle text-sm"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-gray-500 italic mb-4">Belum ada sub pengelola.</p>
            @endif

            <!-- Add Manager Form -->
            <form action="{{ route('projects.addManager', $project) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="text-[10px] text-gray-500 uppercase font-bold">Tambah Sub Pengelola</label>
                    <select name="email" class="w-full mt-1 border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">Pilih Teman...</option>
                        @foreach($friends as $friend)
                            <option value="{{ $friend->email }}">{{ $friend->name }} ({{ $friend->email }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full gradient-bg text-white py-2 rounded-lg text-sm font-bold hover:opacity-90 transition shadow-sm">
                    <i class="fas fa-plus-circle mr-1"></i>Tambah
                </button>
            </form>
        </div>
        @else
            @if($project->managers->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Sub Pengelola</h2>
                <div class="flex -space-x-2 overflow-hidden">
                    @foreach($project->managers as $manager)
                    <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white gradient-bg flex items-center justify-center text-white text-[10px] font-bold" title="{{ $manager->name }}">
                        {{ substr($manager->name, 0, 1) }}
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endif

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Tentang Proyek</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold">Deskripsi</p>
                    <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $project->description ?: 'Tidak ada deskripsi.' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Mulai</p>
                        <p class="text-sm text-gray-800 font-semibold">{{ $project->created_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Deadline</p>
                        <p class="text-sm text-red-600 font-bold">{{ $project->deadline->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateTaskStatus(taskId, status) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
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
                const container = document.getElementById(`task-status-${taskId}`);
                const select = container.querySelector('.status-select');
                
                // Update class warna
                select.className = `status-select px-3 py-1 rounded-full text-[10px] font-bold border-0 cursor-pointer ${data.status_color}`;
                
                // Tampilkan notif sukses (opsional)
                console.log(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memperbarui status');
        });
    }

    function updateProjectLive() {
        fetch('{{ route('live.project', $project->id) }}')
            .then(response => response.json())
            .then(data => {
                // Update Project Progress
                if (data.stats) {
                    const progressText = document.getElementById('live-progress-text');
                    const progressBar = document.getElementById('live-progress-bar');
                    if (progressText) progressText.innerText = data.stats.progress + '%';
                    if (progressBar) {
                        progressBar.style.width = data.stats.progress + '%';
                        if (data.stats.progress == 100) {
                            progressBar.classList.remove('gradient-bg');
                            progressBar.classList.add('bg-green-500');
                        } else {
                            progressBar.classList.remove('bg-green-500');
                            progressBar.classList.add('gradient-bg');
                        }
                    }
                }

                const tableBody = document.getElementById('live-task-table-body');
                const existingTaskIds = new Set();
                
                // Update or Add Tasks
                data.tasks.forEach(task => {
                    existingTaskIds.add(task.id);
                    let row = document.getElementById(`task-row-${task.id}`);
                    
                    if (row) {
                        // Update existing task status
                        const statusContainer = row.querySelector(`#task-status-${task.id}`);
                        if (statusContainer) {
                            const select = statusContainer.querySelector('.status-select');
                            const badge = statusContainer.querySelector('span:not(.text-red-500)');

                            if (select && select.value !== task.status) {
                                select.value = task.status;
                                select.className = `status-select px-3 py-1 rounded-full text-[10px] font-bold border-0 cursor-pointer ${task.status_color}`;
                            } else if (badge) {
                                badge.className = `px-2 py-1 rounded-full text-[10px] font-bold w-fit ${task.status_color}`;
                                badge.innerText = task.status_label;
                            }

                            // Handle late badge
                            let lateBadge = statusContainer.querySelector('.text-red-500');
                            if (task.is_late) {
                                if (!lateBadge) {
                                    const span = document.createElement('span');
                                    span.className = 'text-[9px] text-red-500 font-bold mt-1 uppercase';
                                    span.innerText = 'Terlambat';
                                    statusContainer.appendChild(span);
                                }
                            } else if (lateBadge) {
                                lateBadge.remove();
                            }
                        }
                    } else {
                        // Add new task row
                        // Remove "Belum ada tugas" row if it exists
                        const emptyRow = tableBody.querySelector('td[colspan="5"]')?.parentElement;
                        if (emptyRow) emptyRow.remove();

                        const newRow = document.createElement('tr');
                        newRow.className = 'hover:bg-gray-50 transition';
                        newRow.id = `task-row-${task.id}`;
                        
                        let statusHtml = '';
                        if (task.can_change_status) {
                            statusHtml = `
                                <select onchange="updateTaskStatus(${task.id}, this.value)" 
                                        class="status-select px-3 py-1 rounded-full text-[10px] font-bold border-0 cursor-pointer ${task.status_color}">
                                    <option value="belum_mulai" ${task.status == 'belum_mulai' ? 'selected' : ''}>Belum Mulai</option>
                                    <option value="berjalan" ${task.status == 'berjalan' ? 'selected' : ''}>Berjalan</option>
                                    <option value="selesai" ${task.status == 'selesai' ? 'selected' : ''}>Selesai</option>
                                </select>
                            `;
                        } else {
                            statusHtml = `
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold w-fit ${task.status_color}">
                                    ${task.status_label}
                                </span>
                            `;
                        }

                        if (task.is_late) {
                            statusHtml += '<span class="text-[9px] text-red-500 font-bold mt-1 uppercase">Terlambat</span>';
                        }

                        let actionsHtml = `
                            <a href="${task.show_url}" class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition" title="Diskusi">
                                <i class="fas fa-comments text-xs"></i>
                            </a>
                        `;

                        if (task.can_update) {
                            actionsHtml += `
                                <a href="${task.edit_url}" class="w-8 h-8 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center hover:bg-yellow-100 transition">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                            `;
                        }

                        if (task.can_delete) {
                            actionsHtml += `
                                <form action="${task.delete_url}" method="POST" class="inline" onsubmit="return confirm('Hapus tugas ini?');">
                                    <input type="hidden" name="_token" value="${task.csrf_token}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="w-8 h-8 bg-red-50 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-100 transition" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            `;
                        }

                        newRow.innerHTML = `
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-800">${task.title}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-7 h-7 rounded-full gradient-bg flex items-center justify-center text-white text-[10px] font-bold mr-2">
                                        ${task.user_avatar}
                                    </div>
                                    <span class="text-xs text-gray-600">${task.user_name}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-500">
                                    ${task.deadline}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col" id="task-status-${task.id}">
                                    ${statusHtml}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    ${actionsHtml}
                                </div>
                            </td>
                        `;
                        tableBody.appendChild(newRow);
                    }
                });

                // Remove tasks that no longer exist
                const rows = tableBody.querySelectorAll('tr[id^="task-row-"]');
                rows.forEach(row => {
                    const id = parseInt(row.id.replace('task-row-', ''));
                    if (!existingTaskIds.has(id)) {
                        row.remove();
                    }
                });

                // If no tasks left, show empty message
                if (data.tasks.length === 0 && !tableBody.querySelector('td[colspan="5"]')) {
                    const emptyRow = document.createElement('tr');
                    emptyRow.innerHTML = `
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400 italic text-sm">
                            Belum ada tugas yang ditugaskan.
                        </td>
                    `;
                    tableBody.appendChild(emptyRow);
                }
            })
            .catch(error => console.error('Error fetching project live data:', error));
    }

    // Polling setiap 5 detik
    setInterval(updateProjectLive, 5000);
</script>
@endpush
