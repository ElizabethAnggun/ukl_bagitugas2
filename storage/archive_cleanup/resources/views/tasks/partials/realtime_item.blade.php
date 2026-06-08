<!-- File: resources/views/tasks/partials/realtime_item.blade.php -->
@foreach($newTasks as $task)
<div class="p-5 bg-blue-50/50 border border-blue-100 rounded-2xl mb-4 animate-[pulse_2s_ease-in-out]">
    <div class="flex flex-col gap-3">
        <div class="flex items-center justify-between gap-4">
            <span class="text-[10px] bg-blue-100 text-blue-600 font-extrabold px-2 py-1 rounded-md uppercase tracking-wider">
                Tugas Baru Saja Masuk!
            </span>
            <span class="text-xs font-bold text-rose-500">
                <i class="fas fa-calendar-day"></i> {{ $task->deadline?->format('d M Y') ?? '-' }}
            </span>
        </div>
        <div>
            <p class="font-extrabold text-gray-900 truncate">{{ $task->title }}</p>
            @if(!empty($task->description))
                <p class="text-xs text-gray-500 mt-1">{{ $task->description }}</p>
            @endif
            @if($task->project)
                <p class="text-[11px] uppercase tracking-[0.2em] text-slate-400 mt-2">{{ $task->project->name }}</p>
            @endif
        </div>
    </div>
</div>
@endforeach