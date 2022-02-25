<div x-data="{
    reset: false,
    to_reset() {
        if(confirm('確定是否將系統資料清空?')) {
            this.reset = true;
            window.location.href = '{{ route('material.init') }}';
        }
    }
}" class="flex space-x-4 mt-4">
    @if(config('app.debug'))
    <div>
        <button x-on:click.prevent="to_reset" class="group block max-w-xs rounded-lg p-3 py-2 bg-white ring-1 ring-slate-900/5 shadow space-y-3 hover:bg-main-500">
            <div class="flex items-center space-x-1">
                <svg x-show="!reset" class="h-5 w-5 stroke-main-500 group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <svg x-show="reset" style="display: none;" class="animate-spin h-5 w-5 stroke-main-500 group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <h3 class="hidden lg:inline-block text-sm text-slate-900 font-semibold group-hover:text-white">{{ __('清空') }}</h3>
            </div>
        </button>
    </div>
    @endif
    {{-- 新增素材按鈕 --}}
    @include('materials::items.form.item')
    {{-- 新增標籤按鈕 --}}
    @include('materials::items.form.tag')
    {{-- 切換素材類型按鈕 --}}
    <div class="inline-flex rounded-md shadow-sm" role="group">
        <a href="{{ route('material.items.index', array_merge($query, ['type' => 1])) }}" class="group inline-flex items-center rounded-l-lg p-3 py-2 {{ ($type != 1) ? 'bg-white' : 'bg-main-500 ring-main-500' }} shadow space-y-3 hover:bg-main-500 ring-1 ring-slate-900/5 hover:ring-main-500">
            <span class="flex items-center space-x-1">
                <svg class="h-5 w-5 {{ ($type == 1) ? 'stroke-white' : 'stroke-main-500' }} group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                <span class="hidden lg:inline-block text-sm {{ ($type == 1) ? 'text-white' : 'text-slate-900' }} font-semibold group-hover:text-white">{{ __('Text') }}</span>
            </span>
        </a>
        <a href="{{ route('material.items.index', array_merge($query, ['type' => 2])) }}" class="group inline-flex items-center p-3 py-2  {{ ($type != 2) ? 'bg-white' : 'bg-main-500 ring-main-500' }} shadow space-y-3 hover:bg-main-500 ring-1 ring-slate-900/5 hover:ring-main-500">
            <span class="flex items-center space-x-1">
                <svg class="h-5 w-5 {{ ($type == 2) ? 'stroke-white' : 'stroke-main-500' }} group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="hidden lg:inline-block text-sm {{ ($type == 2) ? 'text-white' : 'text-slate-900' }} font-semibold group-hover:text-white">{{ __('Image') }}</span>
            </span>
        </a>
        <a href="{{ route('material.items.index', array_merge($query, ['type' => 8])) }}" class="group inline-flex items-center rounded-r-lg p-3 py-2  {{ ($type != 8) ? 'bg-white' : 'bg-main-500 ring-main-500' }} shadow space-y-3 hover:bg-main-500 ring-1 ring-slate-900/5 hover:ring-main-500">
            <span class="flex items-center space-x-1">
                <svg class="h-5 w-5 {{ ($type == 8) ? 'stroke-white' : 'stroke-main-500' }} group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                </svg>
                <span class="hidden lg:inline-block text-sm {{ ($type == 8) ? 'text-white' : 'text-slate-900' }} font-semibold group-hover:text-white">{{ __('Video') }}</span>
            </span>
        </a>
    </div>
    <form method="GET" action="{{ route('material.items.index') }}" class="flex space-x-1" role="group">
        @foreach ($query as $k => $v)
            @if ($k != 'search')
            <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
            @endif
        @endforeach
        <label class="relative">
            <span class="sr-only">Search</span>
            <input type="text" name="search" onmouseenter="this.focus(); this.select();" value="{{ request()->input('search') }}" autocomplete="off" class="block bg-white w-full border border-slate-900/10 rounded-md py-2 px-3 shadow-sm text-slate-600 placeholder:text-slate-400 focus:outline-none focus:border-main-500 focus:ring-main-500 focus:ring-1 sm:text-sm" placeholder="{{ __('Search for material title') }}...">
        </label>
        <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm p-2 bg-main-500 text-base font-medium text-white hover:bg-main-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-500 sm:ml-3 sm:w-auto sm:text-sm">
            <span class="flex items-center">
                <svg class="h-5 w-5 fill-white" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                </svg>
            </span>
        </button>
    </form>
</div>
