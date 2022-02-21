{{-- main --}}
<div class="flex space-x-0">
    {{-- list --}}
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8 flex-1">
        <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8 gap-2">
            <div class="max-w-screen-lg flex flex-wrap gap-2 p-1 pt-1.5">
                @foreach ($items as $item)
                <a href="#" class="group relative shadow bg-gray-100/100 bg-stripes bg-stripes-white px-2">
                    @if($item->mediaable)
                    <img class="object-contain max-h-44 h-44" src="{{ $item->mediaable->thumbnail_url }}" />
                    @else
                    <div class="grid max-h-44 h-44 w-44 text-center place-content-center">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="animate-bounce h-5 w-5 stroke-yellow-500 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ __('thumbnailing') }}...</span>
                        </div>
                    </div>
                    @endif
                    <div class="absolute flex justify-between justify-items-end items-end top-0 left-2 right-2 h-full text-sm">
                        <div class="flex p-1">
                            <div class="rounded-full bg-white p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-slate-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="transition-all invisible opacity-0 group-hover:visible group-hover:opacity-100">
                            <button x-on:click.prevent="edit({{ $item->toJson() }})" type="button" class="rounded-lg p-1 px-1.5 bg-white hover:bg-slate-100 text-slate-600 hover:text-slate-700 border border-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-slate-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Edit') }}
                            </button>
                            @if(request()->input('tid') < 0)
                            <button x-on:click.prevent="restore({{ $item->toJson() }})" type="button" data-modal-toggle="defaultModal" class="rounded-lg p-1 px-1.5 bg-white hover:bg-red-100 text-red-500 hover:text-red-700 border border-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-red-600 hover:stroke-red-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
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
                    <div class="fixed z-20 transition-all invisible opacity-0 group-hover:visible flex group-hover:opacity-100 w-full left-0 right-0 bottom-1/4 text-sm p-2">
                        <div class="flex text-slate-600 text-sm font-medium mx-auto gap-2">
                            <div class="rounded-lg p-1 px-1.5 bg-white flex border border-gray-500">
                                <span>
                                    <svg class="h-5 w-5 stroke-slate-400 group-hover:stroke-slate-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <p class="max-w-full sm:max-w-lg md:max-w-screen-md  divide-x truncate whitespace-nowrap">
                                    {{ $item->title }}
                                </p>
                            </div>
                            {{-- tags --}}
                            @if($item->tags_count)
                            <div class="rounded-lg p-1 px-1.5 bg-white flex border border-gray-500">
                                <span>
                                    <svg class="h-5 w-5 stroke-slate-400 group-hover:stroke-slate-500 mr-1" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </span>
                                <p class="max-w-full sm:max-w-lg md:max-w-screen-md  divide-x truncate whitespace-nowrap">
                                @foreach ($item->tags as $tag)
                                <span class="pl-1">{{ $tag->name }}</span>
                                @endforeach
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
