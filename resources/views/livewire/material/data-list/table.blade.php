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
                <div>
                    <div class="overflow-hidden shadow-md sm:rounded-lg">
                        <table class="min-w-full">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-4 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        <a wire:click.prevent="$emitSelf('dataSort', 'title');" href="#" class="hover:underline">
                                            {{ __('material title') }}
                                            @if ($sortby_col == 'title')
                                                @if (!$orderby)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                                </svg>
                                                @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                                </svg>
                                                @endif
                                            @endif
                                        </a>
                                    </th>
                                    @if($trash_flag)
                                    <th scope="col" class="py-4 px-6 text-xs font-medium tracking-wider text-left text-red-600 uppercase dark:text-red-400">
                                        <a wire:click.prevent="$emitSelf('dataSort', 'deleted_at');" href="#" class="hover:underline">
                                            {{ __('deleted at') }}
                                            @if ($sortby_col == 'deleted_at')
                                                @if ($orderby == 'asc')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                                </svg>
                                                @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                                </svg>
                                                @endif
                                            @endif
                                        </a>
                                    </th>
                                    @else
                                    <th scope="col" class="py-4 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        <a wire:click.prevent="$emitSelf('dataSort', 'created_at');" href="#" class="hover:underline">
                                            {{ __('created at') }}
                                            @if ($sortby_col == 'created_at')
                                                @if ($orderby == 'asc')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                                </svg>
                                                @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                                </svg>
                                                @endif
                                            @endif
                                        </a>
                                    </th>
                                    @endif
                                    <th scope="col" class="relative py-4 px-6">
                                        <span class="sr-only">{{ __('Edit') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                <tr wire:key="data-item-{{ $item->id }}" class="border-b odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700 dark:border-gray-600">
                                    <td class="flex p-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        @if ($thumnail = $item->getThumnailUrl(75, 75))
                                            <div class="relative rounded-lg text-center overflow-hidden border w-20 mr-2">
                                                <div class="absolute inset-0 bg-white"></div>
                                                <img class="relative z-10 object-scale-down h-20 w-full" src="{{ $thumnail }}">
                                            </div>
                                        @endif
                                        <div class="max-w-xs" title="{{ $item->title }}">
                                            {{-- title --}}
                                            <p class="truncate">{{ $item->title }}</p>
                                            {{-- format --}}
                                            @if ($item->is_ready)
                                                <span class="bg-yellow-100 text-yellow-800 text-sm font-medium mr-2 px-2.5 py-0.25 rounded dark:bg-yellow-200 dark:text-yellow-900">{{ __('processing...') }}</span>
                                            @else
                                                @include('livewire.material.data-list.table-format')
                                            @endif
                                            {{-- tags --}}
                                            @if($item->tags_count)
                                            <div class="flex mt-2">
                                                @foreach ($item->tags as $tag)
                                                <span class="bg-gray-100 text-gray-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    @if($trash_flag)
                                    <td class="p-4 text-sm text-red-600 whitespace-nowrap dark:text-red-400">
                                        {{ $item->created_at }}
                                    </td>
                                    @else
                                    <td class="p-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                        {{ $item->created_at }}
                                    </td>
                                    @endif
                                    <td class="p-4 text-sm font-medium text-right whitespace-nowrap space-x-4">
                                        <button wire:click.prevent="$emitTo('material.form.edit', 'edit', {{ $item->id }})" type="button" class="text-sky-600 hover:text-sky-700 dark:text-sky-500 dark:hover:underline">{{ __('Edit') }}</button>
                                        @if($trash_flag)
                                        <button wire:click.prevent="restore({{ $item->id }})" type="button" data-modal-toggle="defaultModal" class="text-red-500 hover:text-red-700 dark:text-red-500 dark:hover:underline">{{ __('Restore') }}</button>
                                        @else
                                        <button wire:click.prevent="deleteID({{ $item->id }})" type="button" data-modal-toggle="defaultModal" class="text-red-500 hover:text-red-700 dark:text-red-500 dark:hover:underline">{{ __('Delete') }}</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $items->links() }}
                </div>
            </div>
        </div>
        {{-- editor --}}
        <livewire:material.form.edit/>
    </div>

    <!-- Modal -->
    <div x-show="$wire.delete_id" style="display: none" wire:ignore.self class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
