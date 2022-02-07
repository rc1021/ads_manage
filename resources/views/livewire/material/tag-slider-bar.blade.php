<div x-data class="block max-w-xs mx-auto p-6 bg-whitering-1 ring-slate-900/5 space-y-3">
    <label class="relative block">
        <span class="absolute inset-y-0 left-0 flex items-center pl-2">
        <svg class="h-5 w-5 fill-slate-300" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
        </svg>
        </span>
        <span class="sr-only">Search</span>
        <input wire:model.debounce.250ms="search" type="text" name="search" autocomplete="off" class="block bg-white w-full border border-slate-300 rounded-md py-2 pl-9 pr-3 shadow-sm placeholder:text-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm" placeholder="Search for tag...">
    </label>
    <div class="bg-white px-4 space-y-3">
        <h2 wire:click.prevent="$emit('choiceTag', null);" class="cursor-pointer text-base font-semibold text-slate-900 hover:text-sky-400 dark:text-slate-200 flex items-center space-x-1 {{ $choice_id ? '' : 'text-sky-400' }}">
            {{ __('All Materials') }}
        </h2>
        @foreach ($parents as $parent)
            <h2 wire:key="tag-{{ $parent->id }}" class="text-base font-semibold text-slate-900 dark:text-slate-200">
                {{ $parent->name }}
                @if ($parent->materials_count > 0)
                <span class="text-xs rounded-full px-2 py-1 bg-slate-100 font-semibold text-slate-700">{{ $parent->materials_count }}</span>
                @endif
            </h2>
            @if($items->get($parent->id))
            <ul role="list" class="mt-3 list-disc pl-5 space-y-3 text-slate-600">
                @foreach ($items->get($parent->id) as $item)
                <li wire:key="tag-{{ $item->id }}" wire:click.prevent="$emit('choiceTag', {{ $item->id }});" class="cursor-pointer hover:text-sky-400 {{ $choice_id == $item->id ? 'text-sky-400' : '' }}">
                    {{ $item->name }}
                    @if ($item->materials_count > 0)
                    <span class="text-xs rounded-full px-2 py-1 bg-slate-100 font-semibold text-slate-700">{{ $item->materials_count }}</span>
                    @endif
                </li>
                @endforeach
            </ul>
            @else
            <div class="mt-3 pl-5 space-y-3 text-slate-300">{{ __('empty') }}</div>
            @endif
        @endforeach
        <h2 wire:click.prevent="$emit('choiceTag', -1);" class="cursor-pointer text-base font-semibold text-slate-900 hover:text-sky-400 dark:text-slate-200 flex items-center space-x-1 {{ $choice_id == -1 ? 'text-sky-400' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            {{ __('Material Trash') }}
        </h2>
    </div>
    <hr>
    <div class="bg-white px-4 space-y-3">
        <a href="#" class="items-center text-base font-semibold hover:text-sky-400 text-slate-900 dark:text-slate-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
            </svg>
            <span>廣告樣版管理</span>
        </a>
    </div>
</div>
