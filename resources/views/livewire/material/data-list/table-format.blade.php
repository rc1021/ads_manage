<div class="flex space-x-2 text-slate-400">
    {{-- mimetype --}}
    @if ($mimetype = data_get($item->extra_data, 'origin.mime_type', false))
    <div>
        @if (stripos($mimetype, 'image') !== false)
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-slate-400 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        @elseif (stripos($mimetype, 'video') !== false)
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-slate-400 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
        </svg>
        @endif
        {{ strtoupper(data_get(explode('/', $mimetype), '1', '')) }}
    </div>
    @endif
    {{-- seconds long --}}
    @if ($seconds = data_get($item->extra_data, 'time', false))
    <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-slate-400 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        @if ($seconds > 3600)
        <span>{{ gmdate('H:i:s', $seconds) }}</span>
        @else
        <span>{{ gmdate('i:s', $seconds) }}</span>
        @endif
    </div>
    @endif
    {{-- disk size --}}
    @if ($size = data_get($item->extra_data, 'origin.size', false))
        @if (strlen(floor($size)) > 6)
        <span>{{ number_format($size / 1024 /1024) }}MB</span>
        @else
        <span>{{ number_format($size / 1024) }}KB</span>
        @endif
    @endif
</div>
