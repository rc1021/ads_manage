<div class="block max-w-xs mx-auto p-6 bg-whitering-1 ring-slate-900/5 space-y-3">
    <label class="relative block">
        <span class="absolute inset-y-0 left-0 flex items-center pl-2">
        <svg class="h-5 w-5 fill-slate-300" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
        </svg>
        </span>
        <span class="sr-only">Search</span>
        <input type="text" name="search" autocomplete="off" class="block bg-white w-full border border-slate-300 rounded-md py-2 pl-9 pr-3 shadow-sm placeholder:text-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm" placeholder="Search for tag...">
    </label>
    <div class="bg-white px-4 space-y-4">
        @foreach ($parents as $parent)
        <h2 class="text-base font-semibold text-slate-900 dark:text-slate-200">{{ $parent->name }}</h2>
        @if($items->get($parent->id))
        <ul role="list" class="mt-3 list-disc pl-5 space-y-3 text-slate-500">
            @foreach ($items->get($parent->id) as $item)
            <li><a href="#">{{ $item->name }}</a></li>
            @endforeach
        </ul>
        @endif
        @endforeach
        <h2 class="text-base font-semibold text-slate-900 dark:text-slate-200"><a href="#">{{ __('trash') }}</a></h2>
    </div>
</div>
