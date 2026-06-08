@foreach($newTasks as $task)
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
        <span class="text-xs text-gray-500">{{ optional($task->deadline)->format('d M Y') }}</span>
    </td>
    <td class="px-6 py-4">
        <div class="flex flex-col" id="task-status-{{ $task->id }}">
            <span class="px-2 py-1 rounded-full text-[10px] font-bold w-fit {{ $task->status_color }}">
                {{ $task->status_label }}
            </span>
            @if($task->isLate())
                <span class="text-[9px] text-red-500 font-bold mt-1 uppercase">Terlambat</span>
            @endif
        </div>
    </td>
    <td class="px-6 py-4">
        <a href="{{ route('tasks.show', $task) }}" class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition" title="Diskusi">
            <i class="fas fa-comments text-xs"></i>
        </a>
    </td>
</tr>
@endforeach
