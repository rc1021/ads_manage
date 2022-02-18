<div class="flex space-x-4 mt-4">
    {{-- 新增素材按鈕 --}}
    @include('materials.form.item')
    {{-- 新增標籤按鈕 --}}
    @include('materials.form.tag')
    {{-- 切換素材類型按鈕 --}}
    <div class="inline-flex rounded-md shadow-sm" role="group">
        <a href="{{ route('materials.index', array_merge($query, ['type' => 1])) }}" class="group inline-flex items-center rounded-l-lg p-3 py-2 {{ ($type != 1) ? 'bg-white' : 'bg-main-500 ring-main-500' }} shadow space-y-3 hover:bg-main-500 ring-1 ring-slate-900/5 hover:ring-main-500">
            <span class="flex items-center space-x-1">
                <svg class="h-5 w-5 {{ ($type == 1) ? 'stroke-white' : 'stroke-main-500' }} group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                <span class="hidden lg:inline-block text-sm {{ ($type == 1) ? 'text-white' : 'text-slate-900' }} font-semibold group-hover:text-white">{{ __('Text') }}</span>
            </span>
        </a>
        <a href="{{ route('materials.index', array_merge($query, ['type' => 2])) }}" class="group inline-flex items-center p-3 py-2  {{ ($type != 2) ? 'bg-white' : 'bg-main-500 ring-main-500' }} shadow space-y-3 hover:bg-main-500 ring-1 ring-slate-900/5 hover:ring-main-500">
            <span class="flex items-center space-x-1">
                <svg class="h-5 w-5 {{ ($type == 2) ? 'stroke-white' : 'stroke-main-500' }} group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="hidden lg:inline-block text-sm {{ ($type == 2) ? 'text-white' : 'text-slate-900' }} font-semibold group-hover:text-white">{{ __('Image') }}</span>
            </span>
        </a>
        <a href="{{ route('materials.index', array_merge($query, ['type' => 8])) }}" class="group inline-flex items-center rounded-r-lg p-3 py-2  {{ ($type != 8) ? 'bg-white' : 'bg-main-500 ring-main-500' }} shadow space-y-3 hover:bg-main-500 ring-1 ring-slate-900/5 hover:ring-main-500">
            <span class="flex items-center space-x-1">
                <svg class="h-5 w-5 {{ ($type == 8) ? 'stroke-white' : 'stroke-main-500' }} group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                </svg>
                <span class="hidden lg:inline-block text-sm {{ ($type == 8) ? 'text-white' : 'text-slate-900' }} font-semibold group-hover:text-white">{{ __('Video') }}</span>
            </span>
        </a>
    </div>
</div>
