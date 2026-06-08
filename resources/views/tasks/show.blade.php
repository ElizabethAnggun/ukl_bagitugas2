@extends('layouts.dashboard')

@section('title', 'Detail Tugas')

@section('content')
<!-- Header -->
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <a href="{{ route('tasks.index') }}" class="text-gray-500 hover:text-gray-700 mb-2 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Tugas
        </a>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $task->title }}</h1>
        <p class="text-gray-600 mt-1">
            Proyek: <span class="font-semibold text-indigo-600">{{ $task->project->name }}</span>
        </p>
    </div>
    <div class="flex space-x-3">
        @if($isManager)
            @can('update', $task)
                <a href="{{ route('tasks.edit', $task) }}" class="bg-yellow-500 text-white px-6 py-2 rounded-xl font-medium hover:bg-yellow-600 transition shadow-sm">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            @endcan
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Task Details -->
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>Detail Tugas
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <div class="mt-1 flex items-center" id="live-task-status-container">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $task->status_color }}">
                            {{ $task->status_label }}
                        </span>
                        @if($task->isLate())
                            <span class="ml-2 text-xs text-red-500 font-bold uppercase">
                                <i class="fas fa-exclamation-circle mr-1"></i>Terlambat
                            </span>
                        @endif
                    </div>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Deadline</p>
                    <p class="mt-1 text-gray-800 font-medium">
                        <i class="fas fa-calendar-alt text-red-400 mr-2"></i>
                        {{ $task->deadline->format('d M Y') }}
                    </p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Ditugaskan Ke</p>
                    <div class="mt-1 flex items-center">
                        <div class="w-8 h-8 gradient-bg rounded-full flex items-center justify-center mr-2">
                            <span class="text-white text-xs font-semibold">{{ substr($task->user->name, 0, 1) }}</span>
                        </div>
                        <p class="text-gray-800 font-medium">{{ $task->user->name }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Pemberi Tugas</p>
                    <div class="mt-1 flex items-center">
                        <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mr-2">
                            <i class="fas fa-user-tie text-xs"></i>
                        </div>
                        <p class="text-gray-800 font-medium">{{ $task->project->user->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bukti Pengerjaan Section -->
        <div class="bg-white rounded-2xl shadow-sm p-6 overflow-hidden">
            <div class="flex items-center justify-between mb-4 border-b pb-2">
                <h2 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-file-contract text-green-500 mr-2"></i>Bukti Pengerjaan
                </h2>
                @if($task->completed_at)
                    <span class="text-xs text-gray-500 italic">
                        Diselesaikan pada: {{ $task->completed_at->format('d M Y H:i') }}
                    </span>
                @endif
            </div>

            <!-- List Bukti -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6" id="live-proofs-container">
                @forelse($task->proof_file ?? [] as $file)
                    <div class="flex items-center p-3 border rounded-xl hover:bg-gray-50 transition group cursor-pointer" 
                         onclick="previewProof('{{ asset('storage/' . $file['path']) }}', '{{ $file['name'] }}', '{{ pathinfo($file['path'], PATHINFO_EXTENSION) }}')">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3 text-gray-500">
                            @php
                                $ext = pathinfo($file['path'], PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png']);
                                $isVideo = in_array(strtolower($ext), ['mp4', 'mov', 'avi', 'mkv']);
                            @endphp
                            @if($isImage)
                                <i class="fas fa-image text-blue-400"></i>
                            @elseif($isVideo)
                                <i class="fas fa-video text-purple-500"></i>
                            @elseif(strtolower($ext) === 'pdf')
                                <i class="fas fa-file-pdf text-red-500"></i>
                            @else
                                <i class="fas fa-file-word text-blue-500"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $file['name'] }}</p>
                            <p class="text-[10px] text-gray-400">Diunggah: {{ \Carbon\Carbon::parse($file['uploaded_at'])->diffForHumans() }}</p>
                        </div>
                        <div class="flex space-x-1" onclick="event.stopPropagation()">
                            <a href="{{ route('downloadProof', ['task' => $task->id, 'path' => $file['path'], 'name' => $file['name']]) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Download Langsung">
                                <i class="fas fa-download text-xs"></i>
                            </a>
                                    @if($isManager || Auth::id() === $task->user_id)
                                        <form action="{{ route('deleteProof', $task) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="path" value="{{ $file['path'] }}">
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" onclick="return confirm('Hapus bukti ini?')" title="Hapus">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-6 text-center">
                        <p class="text-gray-400 text-sm italic">Belum ada bukti pengerjaan yang diunggah.</p>
                    </div>
                @endforelse
            </div>

            <!-- Upload Form (Hanya untuk yang ditugaskan atau pengelola) -->
            @if(Auth::id() === $task->user_id || $isManager)
                <div class="bg-gray-50 rounded-2xl p-6 border-2 border-dashed border-gray-200">
                    <form action="{{ route('uploadProof', $task) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mb-3 text-indigo-500">
                                <i class="fas fa-cloud-upload-alt text-2xl"></i>
                            </div>
                            <h3 class="text-sm font-bold text-gray-800 mb-1">Unggah Bukti Pengerjaan</h3>
                            <p class="text-xs text-gray-500 mb-4">Mendukung format JPG, PNG, PDF, DOC, Video (Maks. 5 file, 50MB/file)</p>
                            
                            <input type="file" name="proof_files[]" multiple id="proof_files" class="hidden" onchange="this.form.submit()">
                            <button type="button" onclick="document.getElementById('proof_files').click()" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-semibold text-sm hover:bg-indigo-700 transition shadow-md">
                                <i class="fas fa-plus mr-2"></i>Pilih & Unggah File
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <!-- Preview Modal -->
        <div id="previewModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
            <div class="relative w-full max-w-4xl max-h-[90vh] bg-white rounded-3xl overflow-hidden shadow-2xl flex flex-col">
                <div class="p-4 border-b flex items-center justify-between bg-white sticky top-0 z-10">
                    <h3 id="modalTitle" class="font-bold text-gray-800 truncate pr-8">Preview Bukti</h3>
                    <button onclick="closePreview()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="modalContent" class="flex-1 overflow-auto p-4 flex items-center justify-center bg-gray-50">
                    <!-- Content will be injected here -->
                </div>
                <div class="p-4 border-t bg-white flex justify-end gap-3">
                    <a id="modalDownload" href="#" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-semibold text-sm hover:bg-indigo-700 transition">
                        <i class="fas fa-download mr-2"></i>Download File
                    </a>
                    <button onclick="closePreview()" class="px-6 py-2 border rounded-xl font-semibold text-sm hover:bg-gray-100 transition text-gray-600">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-comments text-indigo-500 mr-2"></i>Diskusi & Komentar
                </h2>
            </div>
            
            <div class="p-6 space-y-6 max-h-[500px] overflow-y-auto" id="live-comments-container">
                @forelse($task->comments as $comment)
                    <div class="flex items-start gap-4" id="comment-{{ $comment->id }}">
                        <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-white
                            {{ $comment->user_id === $task->project->user_id ? 'bg-indigo-500' : 'bg-blue-500' }}">
                            {{ substr($comment->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <div class="bg-gray-50 rounded-2xl p-4 relative group">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-bold text-gray-800 text-sm">
                                        {{ $comment->user->name }}
                                        @if($task->project->isManager($comment->user_id))
                                            <span class="ml-1 text-[10px] bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded-full uppercase">Pengelola</span>
                                        @endif
                                    </span>
                                    <span class="text-[10px] text-gray-400">
                                        {{ $comment->created_at->diffForHumans() }}
                                        @if($comment->updated_at && $comment->updated_at->ne($comment->created_at))
                                            <span class="italic text-[9px] ml-1">(diedit)</span>
                                        @endif
                                    </span>
                                </div>
                                <p class="text-gray-700 text-sm whitespace-pre-wrap" id="comment-text-{{ $comment->id }}">{{ $comment->comment }}</p>

                                @if($comment->user_id === Auth::id())
                                    <div id="edit-form-{{ $comment->id }}" class="hidden mt-2">
                                        <form action="{{ route('comments.update', $comment) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <textarea name="comment" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" rows="2" required>{{ $comment->comment }}</textarea>
                                            <div class="mt-2 flex justify-end space-x-2">
                                                <button type="button" onclick="toggleEdit({{ $comment->id }})" class="px-3 py-1 text-xs font-bold text-gray-500 hover:bg-gray-100 rounded-lg transition">Batal</button>
                                                <button type="submit" class="px-3 py-1 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                                <div class="mt-2 flex items-center space-x-3" id="comment-actions-{{ $comment->id }}">
                                    <button type="button" 
                                            onclick="replyTo('{{ $comment->user->name }}')"
                                            class="text-[10px] text-indigo-600 font-bold hover:underline">
                                        <i class="fas fa-reply mr-1"></i>Balas
                                    </button>
                                    
                                    @if($comment->user_id === Auth::id())
                                        <button type="button" onclick="toggleEdit({{ $comment->id }})" class="text-[10px] text-yellow-600 font-bold hover:underline">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[10px] text-red-500 font-bold hover:underline" onclick="return confirm('Hapus komentar?')">
                                                <i class="fas fa-trash-alt mr-1"></i>Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-comment-dots text-gray-300 text-2xl"></i>
                        </div>
                        <p class="text-gray-400 text-sm">Belum ada komentar. Mulai diskusi sekarang!</p>
                    </div>
                @endforelse
            </div>

            <!-- Add Comment Form -->
            <div class="p-6 bg-gray-50 border-t">
                <form action="{{ route('comments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full flex-shrink-0 gradient-bg flex items-center justify-center font-bold text-white">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <textarea name="comment" 
                                      id="comment-input"
                                      rows="2" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm @error('comment') border-red-500 @enderror"
                                      placeholder="Tulis pesan atau update progres (Gunakan @nama untuk mention)..."
                                      required></textarea>
                            @error('comment')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <div class="mt-2 flex justify-end">
                                <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-xl font-semibold text-sm hover:opacity-90 transition shadow-md">
                                    <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Activity -->
    <div class="space-y-8">
        <!-- Project Members (Mentions Helper) -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-users text-indigo-500 mr-2"></i>Anggota Proyek
            </h2>
            <p class="text-xs text-gray-500 mb-4 italic">Klik nama untuk mention</p>
            <div class="space-y-3">
                @foreach($projectMembers as $member)
                    <button type="button" 
                            onclick="mentionUser('{{ $member->name }}')"
                            class="flex items-center w-full p-2 hover:bg-gray-50 rounded-xl transition text-left group">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 flex-shrink-0
                            {{ $member->id === $task->project->user_id ? 'bg-indigo-100 text-indigo-600' : 'bg-blue-100 text-blue-600' }}">
                            <span class="text-xs font-bold">{{ substr($member->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate group-hover:text-indigo-600">{{ $member->name }}</p>
                            @if($task->project->isManager($member->id))
                                <span class="text-[10px] text-indigo-500 font-bold uppercase">Pengelola</span>
                            @endif
                        </div>
                        <i class="fas fa-at text-gray-300 group-hover:text-indigo-400 text-xs"></i>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-history text-purple-500 mr-2"></i>Log Aktivitas
            </h2>
            
            <div class="space-y-4">
                @forelse($task->activityLogs as $log)
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 flex-shrink-0
                            {{ $log->status == 'selesai' ? 'bg-green-100' : 'bg-red-100' }}">
                            <span class="text-sm {{ $log->status_color }}">{{ $log->status_icon }}</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-800 font-medium">
                                {{ $log->status == 'selesai' ? 'Menyelesaikan tugas' : 'Tugas terlambat' }}
                            </p>
                            <p class="text-[10px] text-gray-400">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 text-sm py-4">Belum ada log aktivitas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto scroll ke bawah pada container komentar
    const container = document.getElementById('live-comments-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }

    /**
     * Fungsi untuk membalas komentar (ngetag user)
     */
    function replyTo(userName) {
        const input = document.getElementById('comment-input');
        input.value = `@${userName} ` + input.value;
        input.focus();
        
        // Scroll ke form input
        input.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    /**
     * Toggle form edit komentar
     */
    function toggleEdit(commentId) {
        const textElement = document.getElementById(`comment-text-${commentId}`);
        const formElement = document.getElementById(`edit-form-${commentId}`);
        const actionsElement = document.getElementById(`comment-actions-${commentId}`);

        if (formElement.classList.contains('hidden')) {
            formElement.classList.remove('hidden');
            textElement.classList.add('hidden');
            actionsElement.classList.add('hidden');
        } else {
            formElement.classList.add('hidden');
            textElement.classList.remove('hidden');
            actionsElement.classList.remove('hidden');
        }
    }

    /**
     * Fungsi untuk mention user dari daftar anggota
     */
    function mentionUser(userName) {
        const input = document.getElementById('comment-input');
        const mention = `@${userName} `;
        
        if (!input.value.includes(mention)) {
            input.value += mention;
        }
        
        input.focus();
    }

    /**
     * Fungsi untuk preview bukti (Modal)
     */
    function previewProof(url, name, ext) {
        const modal = document.getElementById('previewModal');
        const content = document.getElementById('modalContent');
        const title = document.getElementById('modalTitle');
        const downloadBtn = document.getElementById('modalDownload');
        
        title.innerText = name;
        downloadBtn.href = `{{ route('downloadProof', ['task' => $task->id]) }}?path=${encodeURIComponent(url.split('/storage/')[1])}&name=${encodeURIComponent(name)}`;
        
        const extension = ext.toLowerCase();
        let html = '';
        
        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
            html = `<img src="${url}" class="max-w-full max-h-full object-contain rounded-lg shadow-md">`;
        } else if (['mp4', 'mov', 'avi', 'mkv', 'webm'].includes(extension)) {
            html = `
                <video controls class="max-w-full max-h-full rounded-lg shadow-md">
                    <source src="${url}" type="video/${extension === 'mov' ? 'quicktime' : (extension === 'mkv' ? 'x-matroska' : extension)}">
                    Browser Anda tidak mendukung pemutaran video.
                </video>`;
        } else if (extension === 'pdf') {
            html = `<iframe src="${url}" class="w-full h-[60vh] rounded-lg border-0 shadow-md"></iframe>`;
        } else {
            html = `
                <div class="text-center p-8 bg-white rounded-2xl shadow-sm border">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-alt text-gray-300 text-4xl"></i>
                    </div>
                    <p class="text-gray-600 mb-4 font-medium">Pratinjau tidak tersedia untuk format .${ext.toUpperCase()}</p>
                    <p class="text-sm text-gray-400">Silakan download file untuk melihat isinya.</p>
                </div>`;
        }
        
        content.innerHTML = html;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scroll
    }

    function closePreview() {
        const modal = document.getElementById('previewModal');
        const content = document.getElementById('modalContent');
        
        modal.classList.add('hidden');
        content.innerHTML = ''; // Clear content to stop video if playing
        document.body.style.overflow = 'auto'; // Re-enable scroll
    }

    function updateTaskLive() {
        fetch('{{ route('live.task', $task->id) }}')
            .then(response => response.json())
            .then(data => {
                // Update Status
                const statusContainer = document.getElementById('live-task-status-container');
                if (statusContainer) {
                    let lateHtml = data.is_late ? `<span class="ml-2 text-xs text-red-500 font-bold uppercase"><i class="fas fa-exclamation-circle mr-1"></i>Terlambat</span>` : '';
                    statusContainer.innerHTML = `
                        <span class="px-3 py-1 rounded-full text-xs font-medium ${data.status_color}">
                            ${data.status_label}
                        </span>
                        ${lateHtml}
                    `;
                }

                // Update Proofs
                const proofsContainer = document.getElementById('live-proofs-container');
                if (data.proofs && data.proofs.length > 0) {
                    const currentProofCount = proofsContainer.querySelectorAll('.group.cursor-pointer').length;
                    // Update if count or content might have changed
                    if (data.proofs.length !== currentProofCount) {
                        let proofsHtml = '';
                        data.proofs.forEach(file => {
                            const ext = file.path.split('.').pop().toLowerCase();
                            let icon = '<i class="fas fa-file-word text-blue-500"></i>';
                            if (['jpg', 'jpeg', 'png'].includes(ext)) icon = '<i class="fas fa-image text-blue-400"></i>';
                            else if (['mp4', 'mov', 'avi', 'mkv'].includes(ext)) icon = '<i class="fas fa-video text-purple-500"></i>';
                            else if (ext === 'pdf') icon = '<i class="fas fa-file-pdf text-red-500"></i>';

                            let deleteButton = '';
                            if (data.is_manager || data.auth_id === data.task_user_id) {
                                deleteButton = `
                                    <form action="/tasks/${data.id}/proof" method="POST" class="inline">
                                        <input type="hidden" name="_token" value="${data.csrf_token}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="path" value="${file.path}">
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" onclick="return confirm('Hapus bukti ini?')" title="Hapus">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                `;
                            }

                            proofsHtml += `
                                <div class="flex items-center p-3 border rounded-xl hover:bg-gray-50 transition group cursor-pointer" 
                                     onclick="previewProof('/storage/${file.path}', '${file.name}', '${ext}')">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3 text-gray-500">
                                        ${icon}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">${file.name}</p>
                                        <p class="text-[10px] text-gray-400">Diunggah: Baru saja</p>
                                    </div>
                                    <div class="flex space-x-1" onclick="event.stopPropagation()">
                                        <a href="/tasks/${data.id}/proof/download?path=${encodeURIComponent(file.path)}&name=${encodeURIComponent(file.name)}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Download Langsung">
                                            <i class="fas fa-download text-xs"></i>
                                        </a>
                                        ${deleteButton}
                                    </div>
                                </div>
                            `;
                        });
                        proofsContainer.innerHTML = proofsHtml;
                    }
                } else {
                    proofsContainer.innerHTML = `
                        <div class="col-span-full py-6 text-center">
                            <p class="text-gray-400 text-sm italic">Belum ada bukti pengerjaan yang diunggah.</p>
                        </div>
                    `;
                }

                // Update Comments
                const commentsContainer = document.getElementById('live-comments-container');
                const currentCommentCount = commentsContainer.querySelectorAll('.flex.items-start').length;
                
                if (data.comments.length > 0) {
                    // Update only if count changes to avoid jumping
                    if (data.comments.length !== currentCommentCount) {
                        let commentsHtml = '';
                        data.comments.forEach(comment => {
                            const bgClass = comment.user_id === data.project_owner_id ? 'bg-indigo-500' : 'bg-blue-500';
                            const managerLabel = comment.is_manager ? '<span class="ml-1 text-[10px] bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded-full uppercase">Pengelola</span>' : '';
                            const editedLabel = comment.is_edited ? '<span class="italic text-[9px] ml-1">(diedit)</span>' : '';

                            let editHtml = '';
                            if (comment.can_edit) {
                                editHtml = `
                                    <div id="edit-form-${comment.id}" class="hidden mt-2">
                                        <form action="${comment.update_url}" method="POST">
                                            <input type="hidden" name="_token" value="${data.csrf_token}">
                                            <input type="hidden" name="_method" value="PUT">
                                            <textarea name="comment" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" rows="2" required>${comment.comment}</textarea>
                                            <div class="mt-2 flex justify-end space-x-2">
                                                <button type="button" onclick="toggleEdit(${comment.id})" class="px-3 py-1 text-xs font-bold text-gray-500 hover:bg-gray-100 rounded-lg transition">Batal</button>
                                                <button type="submit" class="px-3 py-1 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                `;
                            }

                            let editButton = '';
                            if (comment.can_edit) {
                                editButton = `
                                    <button type="button" onclick="toggleEdit(${comment.id})" class="text-[10px] text-yellow-600 font-bold hover:underline">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>
                                `;
                            }
                            
                            let deleteForm = '';
                            if (comment.can_delete) {
                                deleteForm = `
                                    <form action="${comment.delete_url}" method="POST" class="inline">
                                        <input type="hidden" name="_token" value="${data.csrf_token}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="text-[10px] text-red-500 font-bold hover:underline" onclick="return confirm('Hapus komentar?')">
                                            <i class="fas fa-trash-alt mr-1"></i>Hapus
                                        </button>
                                    </form>
                                `;
                            }

                            commentsHtml += `
                                <div class="flex items-start gap-4" id="comment-${comment.id}">
                                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-white ${bgClass}">
                                        ${comment.avatar}
                                    </div>
                                    <div class="flex-1">
                                        <div class="bg-gray-50 rounded-2xl p-4 relative group">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="font-bold text-gray-800 text-sm">
                                                    ${comment.user_name}
                                                    ${managerLabel}
                                                </span>
                                                <span class="text-[10px] text-gray-400">
                                                    ${comment.created_at_human}
                                                    ${editedLabel}
                                                </span>
                                            </div>
                                            <p class="text-gray-700 text-sm whitespace-pre-wrap" id="comment-text-${comment.id}">${comment.comment}</p>
                                            ${editHtml}
                                            <div class="mt-2 flex items-center space-x-3" id="comment-actions-${comment.id}">
                                                <button type="button" onclick="replyTo('${comment.user_name}')" class="text-[10px] text-indigo-600 font-bold hover:underline">
                                                    <i class="fas fa-reply mr-1"></i>Balas
                                                </button>
                                                ${editButton}
                                                ${deleteForm}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        
                        const wasAtBottom = commentsContainer.scrollHeight - commentsContainer.scrollTop <= commentsContainer.clientHeight + 100;
                        
                        commentsContainer.innerHTML = commentsHtml;
                        
                        // Auto scroll to bottom if user was already at bottom
                        if (wasAtBottom) {
                            commentsContainer.scrollTop = commentsContainer.scrollHeight;
                        }
                    }
                } else {
                    commentsContainer.innerHTML = `
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-comment-dots text-gray-300 text-2xl"></i>
                            </div>
                            <p class="text-gray-400 text-sm">Belum ada komentar. Mulai diskusi sekarang!</p>
                        </div>
                    `;
                }
            })
            .catch(error => console.error('Error fetching task live data:', error));
    }

    // Polling setiap 5 detik
    setInterval(updateTaskLive, 5000);
</script>
@endpush
