@extends('layouts.dashboard')

@section('title', 'Daftar Tugas')

@section('content')
<!-- Ambient Background Glow -->
<div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-400/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-cyan-400/20 rounded-full blur-[120px]"></div>
</div>

<!-- Header Ultra-Modern -->
<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-10 relative z-10">
    <div>
        <h1 class="text-4xl md:text-4xl font-extrabold text-gray-900 tracking-tight pb-2 mb-1">
            Daftar Tugas
        </h1>
        <p class="text-gray-500 text-lg font-medium">Pantau deadline dan kelola eksekusi tugas tim Anda.</p>
    </div>
    
    <a href="{{ route('tasks.create') }}" class="mt-6 sm:mt-0 relative group inline-flex items-center justify-center">
        <!-- Efek Glow di belakang tombol -->
        <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-2xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
        <button class="relative bg-gradient-to-r from-[#1d61bd] to-[#0ea0d8] text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-xl hover:scale-[1.02] transition-all duration-300 flex items-center gap-3">
            <i class="fas fa-plus"></i> Tambah Tugas
        </button>
    </a>
</div>

<!-- Filter & Stats (Glassmorphism Cards) -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10 relative z-10">
    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] p-6 border border-white shadow-sm flex flex-col items-center justify-center hover:-translate-y-1 transition-transform duration-300">
        <p class="text-4xl font-extrabold text-gray-800 mb-1" id="live-total-tasks">{{ $tasks->count() }}</p>
        <p class="text-xs uppercase tracking-widest font-bold text-gray-400">Total Tugas</p>
    </div>
    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] p-6 border border-white shadow-sm flex flex-col items-center justify-center hover:-translate-y-1 transition-transform duration-300">
        <p class="text-4xl font-extrabold text-blue-600 mb-1" id="live-running-tasks">{{ $tasks->where('status', 'berjalan')->count() }}</p>
        <p class="text-xs uppercase tracking-widest font-bold text-blue-400">Berjalan</p>
    </div>
    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] p-6 border border-white shadow-sm flex flex-col items-center justify-center hover:-translate-y-1 transition-transform duration-300">
        <p class="text-4xl font-extrabold text-emerald-500 mb-1" id="live-completed-tasks">{{ $tasks->where('status', 'selesai')->count() }}</p>
        <p class="text-xs uppercase tracking-widest font-bold text-emerald-400">Selesai</p>
    </div>
    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] p-6 border border-white shadow-sm flex flex-col items-center justify-center hover:-translate-y-1 transition-transform duration-300">
        <p class="text-4xl font-extrabold text-rose-500 mb-1" id="live-late-tasks">{{ $tasks->filter->isLate()->count() }}</p>
        <p class="text-xs uppercase tracking-widest font-bold text-rose-400">Terlambat</p>
    </div>
</div>

<!-- Tasks Table Modern -->
<div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-lg shadow-blue-900/5 border border-white/80 relative z-10 overflow-hidden">
    <div class="p-8 border-b border-gray-100/50 flex items-center justify-between bg-white/50">
        <h2 class="text-xl font-bold text-gray-800 flex items-center">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 mr-4">
                <i class="fas fa-list-ul"></i>
            </div>
            Daftar Pekerjaan
        </h2>
    </div>
    
    <div class="p-2 md:p-6" id="live-tasks-container">
        @if($tasks->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full border-separate border-spacing-y-3">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tugas & Proyek</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Pelaksana</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tenggat Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider rounded-r-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="live-task-table-body">
                        @foreach($tasks as $task)
                        <tr class="bg-white hover:bg-blue-50/50 transition-colors duration-300 shadow-sm rounded-2xl group" id="task-row-{{ $task->id }}">
                            <!-- Judul & Proyek -->
                            <td class="px-6 py-4 rounded-l-2xl">
                                <p class="font-extrabold text-gray-900 text-base mb-1">{{ $task->title }}</p>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-indigo-50/80 text-indigo-600 text-xs font-semibold border border-indigo-100">
                                    <i class="fas fa-folder mr-1.5 opacity-70"></i> {{ $task->project->name }}
                                </span>
                            </td>
                            
                            <!-- User -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-9 h-9 bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] rounded-full flex items-center justify-center mr-3 shadow-md">
                                        <span class="text-white text-xs font-bold">{{ substr($task->user->name, 0, 1) }}</span>
                                    </div>
                                    <span class="font-semibold text-gray-700">{{ $task->user->name }}</span>
                                </div>
                            </td>
                            
                            <!-- Deadline -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold {{ $task->isLate() ? 'text-rose-600' : 'text-gray-600' }}">
                                        <i class="fas fa-calendar-day mr-2 opacity-70"></i>{{ $task->deadline->format('d M Y') }}
                                    </span>
                                    @if($task->isLate())
                                        <span class="text-[10px] text-rose-500 font-bold mt-1 uppercase tracking-wider">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Melewati Batas
                                        </span>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Status Dropdown/Pill -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col items-start">
                                    @can('changeStatus', $task)
                                        <select onchange="updateStatus({{ $task->id }}, this.value)" 
                                                class="status-select px-4 py-2 rounded-xl text-xs font-bold border border-transparent hover:border-gray-200 cursor-pointer shadow-sm transition-all outline-none focus:ring-2 focus:ring-blue-500 {{ $task->status_color }}">
                                            <option value="belum_mulai" {{ $task->status == 'belum_mulai' ? 'selected' : '' }}>Belum Mulai</option>
                                            <option value="berjalan" {{ $task->status == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                            <option value="selesai" {{ $task->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    @else
                                        <span class="px-4 py-2 rounded-xl text-xs font-bold shadow-sm {{ $task->status_color }}">
                                            {{ $task->status_label }}
                                        </span>
                                    @endcan
                                </div>
                            </td>
                            
                            <!-- Action Buttons -->
                            <td class="px-6 py-4 rounded-r-2xl text-right">
                                <!-- Opacity diubah agar tombol selalu tampil -->
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('tasks.show', $task) }}" class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white hover:shadow-lg hover:shadow-blue-200 transition-all" title="Detail & Diskusi">
                                        <i class="fas fa-comment-dots"></i>
                                    </a>
                                    @can('update', $task)
                                        <a href="{{ route('tasks.edit', $task) }}" class="w-10 h-10 bg-yellow-50 text-yellow-600 rounded-xl flex items-center justify-center hover:bg-yellow-500 hover:text-white hover:shadow-lg hover:shadow-yellow-200 transition-all" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $task)
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tugas ini permanen?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-500 hover:text-white hover:shadow-lg hover:shadow-rose-200 transition-all" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                                @if(!Auth::user()->can('update', $task) && !Auth::user()->can('delete', $task))
                                    <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider block mt-2">Read Only</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Empty State Ultra Modern -->
            <div class="relative w-full max-w-2xl mx-auto my-12 bg-gray-50/50 rounded-[3rem] p-16 text-center border border-dashed border-gray-200 overflow-hidden">
                <div class="relative z-10">
                    <div class="w-32 h-32 mx-auto mb-8 relative">
                        <div class="absolute inset-0 bg-blue-100 rounded-full blur-2xl animate-pulse"></div>
                        <div class="relative z-10 w-full h-full bg-white rounded-full shadow-xl flex items-center justify-center text-[#1d61bd]">
                            <i class="fas fa-clipboard-check text-5xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-extrabold text-gray-900 mb-4">Belum Ada Tugas</h3>
                    <p class="text-lg text-gray-500 mb-10 max-w-md mx-auto">Mulai delegasikan pekerjaan kepada tim Anda untuk menjaga produktivitas proyek.</p>
                    <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-3 bg-gradient-to-r from-[#1d61bd] to-[#0ea0d8] text-white px-10 py-4 rounded-2xl font-bold text-lg hover:opacity-90 transition-all hover:scale-105 shadow-xl shadow-blue-200">
                        <i class="fas fa-plus"></i> Buat Tugas Baru
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    /**
     * Update status tugas via AJAX
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
                select.className = `status-select px-4 py-2 rounded-xl text-xs font-bold border border-transparent hover:border-gray-200 cursor-pointer shadow-sm transition-all outline-none focus:ring-2 focus:ring-blue-500 ${data.status_color}`;
                
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

    /**
     * Update data realtime setiap 5 detik
     */
    function updateTasksLive() {
        fetch('{{ route('live.tasks_list') }}')
            .then(response => response.json())
            .then(data => {
                // Update Statistik
                if (data.stats) {
                    document.getElementById('live-total-tasks').innerText = data.stats.total;
                    document.getElementById('live-running-tasks').innerText = data.stats.berjalan;
                    document.getElementById('live-completed-tasks').innerText = data.stats.selesai;
                    document.getElementById('live-late-tasks').innerText = data.stats.terlambat;
                }

                const container = document.getElementById('live-tasks-container');
                const tableBody = document.getElementById('live-task-table-body');
                const existingTaskIds = new Set();

                if (data.tasks.length > 0) {
                    // Jika sebelumnya kosong, buat struktur tabel
                    if (!tableBody) {
                        container.innerHTML = `
                            <div class="overflow-x-auto">
                                <table class="w-full border-separate border-spacing-y-3">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tugas & Proyek</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Pelaksana</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tenggat Waktu</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider rounded-r-xl">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="live-task-table-body"></tbody>
                                </table>
                            </div>
                        `;
                    }
                    
                    const currentTableBody = document.getElementById('live-task-table-body');
                    
                    data.tasks.forEach(task => {
                        existingTaskIds.add(task.id);
                        let row = document.getElementById(`task-row-${task.id}`);
                        
                        if (row) {
                            // Update Status
                            const select = row.querySelector('.status-select');
                            const badge = row.querySelector('span.rounded-xl');
                            
                            if (select && select.value !== task.status) {
                                select.value = task.status;
                                select.className = `status-select px-4 py-2 rounded-xl text-xs font-bold border border-transparent hover:border-gray-200 cursor-pointer shadow-sm transition-all outline-none focus:ring-2 focus:ring-blue-500 ${task.status_color}`;
                            } else if (badge && !select) {
                                badge.className = `px-4 py-2 rounded-xl text-xs font-bold shadow-sm ${task.status_color}`;
                                badge.innerText = task.status_label;
                            }

                            // Update Deadline & Late Info
                            const deadlineCell = row.cells[2];
                            const lateText = task.is_late ? 'text-rose-600' : 'text-gray-600';
                            let lateBadgeHtml = '';
                            if (task.is_late) {
                                lateBadgeHtml = `
                                    <span class="text-[10px] text-rose-500 font-bold mt-1 uppercase tracking-wider">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Melewati Batas
                                    </span>
                                `;
                            }
                            
                            deadlineCell.innerHTML = `
                                <div class="flex flex-col">
                                    <span class="font-bold ${lateText}">
                                        <i class="fas fa-calendar-day mr-2 opacity-70"></i>${task.deadline}
                                    </span>
                                    ${lateBadgeHtml}
                                </div>
                            `;
                        } else {
                            // Tambah Baris Baru
                            const newRow = document.createElement('tr');
                            newRow.className = 'bg-white hover:bg-blue-50/50 transition-colors duration-300 shadow-sm rounded-2xl group';
                            newRow.id = `task-row-${task.id}`;
                            
                            let statusHtml = '';
                            if (task.can_change_status) {
                                statusHtml = `
                                    <select onchange="updateStatus(${task.id}, this.value)" 
                                            class="status-select px-4 py-2 rounded-xl text-xs font-bold border border-transparent hover:border-gray-200 cursor-pointer shadow-sm transition-all outline-none focus:ring-2 focus:ring-blue-500 ${task.status_color}">
                                        <option value="belum_mulai" ${task.status == 'belum_mulai' ? 'selected' : ''}>Belum Mulai</option>
                                        <option value="berjalan" ${task.status == 'berjalan' ? 'selected' : ''}>Berjalan</option>
                                        <option value="selesai" ${task.status == 'selesai' ? 'selected' : ''}>Selesai</option>
                                    </select>
                                `;
                            } else {
                                statusHtml = `
                                    <span class="px-4 py-2 rounded-xl text-xs font-bold shadow-sm ${task.status_color}">
                                        ${task.status_label}
                                    </span>
                                `;
                            }

                            let actionsHtml = `
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="${task.show_url}" class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white hover:shadow-lg hover:shadow-blue-200 transition-all" title="Detail & Diskusi">
                                        <i class="fas fa-comment-dots"></i>
                                    </a>
                            `;

                            if (task.can_update) {
                                actionsHtml += `
                                    <a href="${task.edit_url}" class="w-10 h-10 bg-yellow-50 text-yellow-600 rounded-xl flex items-center justify-center hover:bg-yellow-500 hover:text-white hover:shadow-lg hover:shadow-yellow-200 transition-all" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                `;
                            }

                            if (task.can_delete) {
                                actionsHtml += `
                                    <form action="${task.delete_url}" method="POST" class="inline" onsubmit="return confirm('Hapus tugas ini permanen?');">
                                        <input type="hidden" name="_token" value="${task.csrf_token}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-500 hover:text-white hover:shadow-lg hover:shadow-rose-200 transition-all" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                `;
                            }
                            
                            actionsHtml += '</div>';
                            
                            if (!task.can_update && !task.can_delete) {
                                actionsHtml += '<span class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider block mt-2 text-right">Read Only</span>';
                            }

                            newRow.innerHTML = `
                                <td class="px-6 py-4 rounded-l-2xl">
                                    <p class="font-extrabold text-gray-900 text-base mb-1">${task.title}</p>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-indigo-50/80 text-indigo-600 text-xs font-semibold border border-indigo-100">
                                        <i class="fas fa-folder mr-1.5 opacity-70"></i> ${task.project_name}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-9 h-9 bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] rounded-full flex items-center justify-center mr-3 shadow-md">
                                            <span class="text-white text-xs font-bold">${task.user_avatar}</span>
                                        </div>
                                        <span class="font-semibold text-gray-700">${task.user_name}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold ${task.is_late ? 'text-rose-600' : 'text-gray-600'}">
                                            <i class="fas fa-calendar-day mr-2 opacity-70"></i>${task.deadline}
                                        </span>
                                        ${task.is_late ? '<span class="text-[10px] text-rose-500 font-bold mt-1 uppercase tracking-wider"><i class="fas fa-exclamation-triangle mr-1"></i>Melewati Batas</span>' : ''}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-start">
                                        ${statusHtml}
                                    </div>
                                </td>
                                <td class="px-6 py-4 rounded-r-2xl text-right">
                                    ${actionsHtml}
                                </td>
                            `;
                            currentTableBody.prepend(newRow);
                        }
                    });

                    // Hapus baris yang sudah tidak ada
                    const rows = currentTableBody.querySelectorAll('tr[id^="task-row-"]');
                    rows.forEach(row => {
                        const id = parseInt(row.id.replace('task-row-', ''));
                        if (!existingTaskIds.has(id)) {
                            row.remove();
                        }
                    });
                } else {
                    // Tampilkan Empty State jika tidak ada tugas
                    container.innerHTML = `
                        <div class="relative w-full max-w-2xl mx-auto my-12 bg-gray-50/50 rounded-[3rem] p-16 text-center border border-dashed border-gray-200 overflow-hidden">
                            <div class="relative z-10">
                                <div class="w-32 h-32 mx-auto mb-8 relative">
                                    <div class="absolute inset-0 bg-blue-100 rounded-full blur-2xl animate-pulse"></div>
                                    <div class="relative z-10 w-full h-full bg-white rounded-full shadow-xl flex items-center justify-center text-[#1d61bd]">
                                        <i class="fas fa-clipboard-check text-5xl"></i>
                                    </div>
                                </div>
                                <h3 class="text-3xl font-extrabold text-gray-900 mb-4">Belum Ada Tugas</h3>
                                <p class="text-lg text-gray-500 mb-10 max-w-md mx-auto">Mulai delegasikan pekerjaan kepada tim Anda untuk menjaga produktivitas proyek.</p>
                                <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-3 bg-gradient-to-r from-[#1d61bd] to-[#0ea0d8] text-white px-10 py-4 rounded-2xl font-bold text-lg hover:opacity-90 transition-all hover:scale-105 shadow-xl shadow-blue-200">
                                    <i class="fas fa-plus"></i> Buat Tugas Baru
                                </a>
                            </div>
                        </div>
                    `;
                }
            })
            .catch(error => console.error('Error fetching live tasks:', error));
    }

    // Jalankan polling setiap 5 detik
    setInterval(updateTasksLive, 5000);
 </script>
@endpush
@endsection