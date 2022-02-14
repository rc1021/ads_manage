<div x-data>
    {{-- toastr --}}
    @include('livewire.toastr')
    {{-- main --}}
    <div class="flex space-x-0">
        {{-- list --}}
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8 flex-1">
            <div class="inline-block py-2 min-w-full sm:px-6 lg:px-8 space-y-4">
                <div wire:loading>
                    <svg role="status" class="h-8 w-8 animate-spin mr-2 text-gray-200 dark:text-gray-600 fill-sky-500" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                </div>

                <div class="max-w-screen-lg divide-y">
                    @foreach ($items as $item)
                    <div class="group relative flex hover:bg-stone-200 overflow-hidden p-1 pt-1.5">
                        <div class="flex-1 flex items-center gap-1">
                            <p title="{{ $item->title }}" class="shrink-0 truncate">{{ $item->title }}</p>
                        </div>
                        <div class="absolute bottom-0.5 right-0.5 flex-none hidden group-hover:inline-flex items-center text-sm font-medium min-w-max text-right whitespace-nowrap space-x-1">
                            {{-- tags --}}
                            @if($item->tags_count)
                            <div class="rounded-lg p-1 px-1.5 bg-white flex border border-gray-500">
                                <span>
                                    <svg class="h-5 w-5 stroke-stone-400 group-hover:stroke-stone-500 mr-1" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </span>
                                <p class="max-w-lg divide-x truncate text-stone-600 text-sm font-medium whitespace-nowrap">
                                @foreach ($item->tags as $tag)
                                <span class="pl-1">{{ $tag->name }}</span>
                                @endforeach
                                </p>
                            </div>
                            @endif
                            <button wire:click.prevent="$emitTo('material.form.edit', 'edit', {{ $item->id }})" type="button" class="rounded-lg p-1 px-1.5 bg-white hover:bg-sky-100 text-sky-600 hover:text-sky-700 border border-gray-500">{{ __('Edit') }}</button>
                            @if($trash_flag)
                            <button wire:click.prevent="restore({{ $item->id }})" type="button" data-modal-toggle="defaultModal" class="rounded-lg p-1 px-1.5 bg-white hover:bg-red-100 text-red-500 hover:text-red-700 border border-gray-500">{{ __('Restore') }}</button>
                            @else
                            <button wire:click.prevent="deleteID({{ $item->id }})" type="button" data-modal-toggle="defaultModal" class="rounded-lg p-1 px-1.5 bg-white hover:bg-red-100 text-red-500 hover:text-red-700 border border-gray-500">{{ __('Delete') }}</button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- editor --}}
        <livewire:material.form.edit/>
    </div>

    <!-- Modal -->
    <div x-show="$wire.delete_id" style="display: none" wire:ignore.self class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
            <button wire:click.prevent="delete($event)" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
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
