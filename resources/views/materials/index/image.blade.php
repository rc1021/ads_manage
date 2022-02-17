<div x-data="materials_list_image()">
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
                        <div class="absolute grid justify-items-end items-end transition-all invisible opacity-0 group-hover:visible group-hover:opacity-100 top-0 left-0 w-full h-full text-sm bg-white/75">
                            <div>
                                <button x-on:click="edit({{ $item->id }})" type="button" class="rounded-lg p-1 px-1.5 bg-white hover:bg-slate-100 text-slate-600 hover:text-slate-700 border border-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-slate-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ __('Edit') }}
                                </button>
                                @if($tag_id < 0)
                                <button  x-on:click.prevent="restore({{ $item->id }})" type="button" data-modal-toggle="defaultModal" class="rounded-lg p-1 px-1.5 bg-white hover:bg-red-100 text-red-500 hover:text-red-700 border border-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-red-600 hover:stroke-red-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                                    </svg>
                                    {{ __('Restore') }}
                                </button>
                                @else
                                <button  x-on:click.prevent="delete_id = {{ $item->id }}" type="button" data-modal-toggle="defaultModal" class="rounded-lg p-1 px-1.5 bg-white hover:bg-red-100 text-red-500 hover:text-red-700 border border-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-red-600 hover:stroke-red-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    {{ __('Delete') }}
                                </button>
                                @endif
                            </div>
                        </div>
                        <div class="fixed z-20 transition-all invisible opacity-0 group-hover:visible flex group-hover:opacity-100 w-full left-0 right-0 bottom-1/4 text-sm p-2">
                            {{-- tags --}}
                            @if($item->tags_count)
                            <div class="rounded-lg p-1 px-1.5 bg-white flex border border-gray-500 mx-auto">
                                <span>
                                    <svg class="h-5 w-5 stroke-stone-400 group-hover:stroke-stone-500 mr-1" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </span>
                                <p class="max-w-full sm:max-w-lg md:max-w-screen-md  divide-x truncate text-stone-600 text-sm font-medium whitespace-nowrap">
                                @foreach ($item->tags as $tag)
                                <span class="pl-1">{{ $tag->name }}</span>
                                @endforeach
                                </p>
                            </div>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- editor --}}
        {{-- <livewire:material.form.edit/> --}}
    </div>

    <!-- Modal -->
    <div x-show="delete_id != null" style="display: none" class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                    {{ __('drop material to the trash') }}
                </h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">
                    {{ __('Are you sure if you put it in the trash?') }}
                    </p>
                </div>
                </div>
            </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button x-on:click.prevent="delete" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                {{ __('Drop') }}
            </button>
            <button wire:click.prevent="deleteID" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                {{ __('Cancel') }}
            </button>
            </div>
        </div>
        </div>
    </div>
</div>
<script>
    function materials_list_image () {
        return {
            delete_id: null,
            restore() {

            },
            delete() {

            }
        }
    }
</script>
