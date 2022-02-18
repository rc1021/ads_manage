{{-- main --}}
<div class="flex space-x-0">
    {{-- list --}}
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8 flex-1">
        <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8 space-y-4">
            <div class="max-w-screen-lg divide-y">
                @foreach ($items as $item)
                <div class="group relative flex hover:bg-slate-200 overflow-hidden p-1 pt-1.5">
                    <div class="flex-1 flex items-center gap-1">
                        <p title="{{ $item->title }}" class="shrink-0 truncate">{{ $item->title }}</p>
                    </div>
                    <div class="absolute bottom-0.5 right-0.5 flex-none hidden group-hover:inline-flex items-center text-sm font-medium min-w-max text-right whitespace-nowrap space-x-1">
                        {{-- tags --}}
                        @if($item->tags_count)
                        <div class="rounded-lg p-1 px-1.5 bg-white flex border border-gray-500">
                            <span>
                                <svg class="h-5 w-5 stroke-slate-400 group-hover:stroke-slate-500 mr-1" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </span>
                            <p class="max-w-lg divide-x truncate text-slate-600 text-sm font-medium whitespace-nowrap">
                            @foreach ($item->tags as $tag)
                            <span class="pl-1">{{ $tag->name }}</span>
                            @endforeach
                            </p>
                        </div>
                        @endif
                        <button x-on:click.prevent="edit({{ $item->toJson() }})" type="button" class="rounded-lg p-1 px-1.5 bg-white hover:bg-slate-100 text-slate-600 hover:text-slate-700 border border-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-slate-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Edit') }}
                        </button>
                        @if(request()->input('tid') < 0)
                        <button  x-on:click.prevent="restore({{ $item->id }})" type="button" data-modal-toggle="defaultModal" class="rounded-lg p-1 px-1.5 bg-white hover:bg-red-100 text-red-500 hover:text-red-700 border border-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-red-600 hover:stroke-red-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                            </svg>
                            {{ __('Restore') }}
                        </button>
                        @else
                        <button x-on:click.prevent="drop({{ $item->toJson() }})" type="button" data-modal-toggle="defaultModal" class="rounded-lg p-1 px-1.5 bg-white hover:bg-red-100 text-red-500 hover:text-red-700 border border-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-red-600 hover:stroke-red-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            {{ __('Delete') }}
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
