<div x-data class="flex space-x-4 mt-4">
    {{-- 新增素材按鈕 --}}
    <livewire:material.form.item/>
    {{-- 新增標籤按鈕 --}}
    <livewire:material.form.tag/>
    {{-- 切換素材類型按鈕 --}}
    <div class="inline-flex rounded-md shadow-sm" role="group">
        <button x-bind:class="{'bg-sky-500 ring-sky-500': $wire.type == 1 }" wire:click.prevent="changeType(1)" type="button" class="group inline-flex items-center rounded-l-lg p-3 py-2 bg-white ring-1 ring-slate-900/5 shadow space-y-3 hover:bg-sky-500 hover:ring-sky-500">
            <div class="flex items-center space-x-1">
                <svg class="h-5 w-5 {{ ($type == 1) ? 'stroke-white' : 'stroke-sky-500' }} group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                <span class="hidden lg:inline-block text-sm {{ ($type == 1) ? 'text-white' : 'text-slate-900' }} font-semibold group-hover:text-white">{{ __('Text') }}</span>
            </div>
        </button>
        <button x-bind:class="{'bg-sky-500 ring-sky-500': $wire.type == 2 }" wire:click.prevent="changeType(2)" type="button" class="group inline-flex items-center p-3 py-2 bg-white ring-1 ring-slate-900/5 shadow space-y-3 hover:bg-sky-500 hover:ring-sky-500">
            <div class="flex items-center space-x-1">
                <svg class="h-5 w-5 {{ ($type == 2) ? 'stroke-white' : 'stroke-sky-500' }} group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="hidden lg:inline-block text-sm {{ ($type == 2) ? 'text-white' : 'text-slate-900' }} font-semibold group-hover:text-white">{{ __('Image') }}</span>
            </div>
        </button>
        <button x-bind:class="{'bg-sky-500 ring-sky-500': $wire.type == 8 }" wire:click.prevent="changeType(8);" type="button" class="group inline-flex items-center rounded-r-lg p-3 py-2 bg-white ring-1 ring-slate-900/5 shadow space-y-3 hover:bg-sky-500 hover:ring-sky-500">
            <div class="flex items-center space-x-1">
                <svg class="h-5 w-5 {{ ($type == 8) ? 'stroke-white' : 'stroke-sky-500' }} group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                </svg>
                <span class="hidden lg:inline-block text-sm {{ ($type == 8) ? 'text-white' : 'text-slate-900' }} font-semibold group-hover:text-white">{{ __('Video') }}</span>
            </div>
        </button>
    </div>
    {{-- 切換素材顯示方式按鈕 --}}
    {{-- <div class="inline-flex rounded-md shadow-sm" role="group">
        <button wire:click.prevent="changeDisplay(0);" x-bind:class="{'bg-sky-500 ring-sky-500': $wire.display == 0 }" type="button" class="group inline-flex items-center rounded-l-lg p-3 py-2 bg-white ring-1 ring-slate-900/5 shadow space-y-3 hover:bg-sky-500 hover:ring-sky-500">
            <div class="flex items-center space-x-1">
                <svg class="h-5 w-5 {{ ($display == 0) ? 'stroke-white' : 'stroke-slate-900' }} group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </div>
        </button>
        <button wire:click.prevent="changeDisplay(1);" x-bind:class="{'bg-sky-500 ring-sky-500': $wire.display == 1 }" type="button" class="group inline-flex items-center rounded-r-lg p-3 py-2 bg-white ring-1 ring-slate-900/5 shadow space-y-3 hover:bg-sky-500 hover:ring-sky-500">
            <div class="flex items-center space-x-1">
                <svg class="h-5 w-5 {{ ($display == 1) ? 'stroke-white' : 'stroke-slate-900' }} group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </div>
        </button>
    </div> --}}
    <button wire:click.prevent="dataReload" type="button" class="group inline-flex items-center rounded-lg p-3 py-2 bg-white ring-1 ring-slate-900/5 shadow space-y-3 hover:bg-sky-500 hover:ring-sky-500">
        <div class="flex items-center space-x-1">
            <svg class="h-5 w-5 stroke-slate-900 group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </div>
    </button>
</div>
