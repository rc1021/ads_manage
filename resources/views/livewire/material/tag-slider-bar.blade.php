<div x-data class="block max-w-xs mx-auto p-6 bg-whitering-1 ring-slate-900/5 space-y-3">
    {{-- toastr --}}
    @include('livewire.toastr')
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
        <a wire:click.prevent="$toggle('is_edit')" href="#" class="items-center text-base font-semibold text-slate-900 dark:text-slate-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            <span>管理標籤</span>
            @if($is_edit)
            <span class="inline-flex items-center p-1 mr-2 text-sm font-semibold text-sky-800 bg-sky-100 rounded-full dark:bg-sky-200 dark:text-sky-800">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
            </span>
            @endif
        </a>
    </div>
    <hr>
    <div class="bg-white px-4 space-y-3">
        <h2 wire:click.prevent="$emit('choiceTag', null);" class="cursor-pointer text-base font-semibold text-slate-900 hover:text-sky-400 dark:text-slate-200 flex items-center space-x-1 {{ $choice_id ? '' : 'text-sky-400' }}">
            {{ __('All Materials') }}
        </h2>
        @foreach ($parents as $parent)
            <h2 wire:key="tag-{{ $parent->id }}" class="text-base font-semibold text-slate-900 dark:text-slate-200 group">
                {{ $parent->name }}
                @if ($parent->materials_count > 0)
                <span class="text-xs rounded-full px-2 py-1 bg-slate-100 font-semibold text-slate-700">{{ $parent->materials_count }}</span>
                @endif
                @if($is_edit)
                <div class="flex space-x-2 font-normal text-sm">
                    <a href="#" wire:click.prevent="edit({{ $parent->id }})" class="text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-slate-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('Edit') }}
                    </a>
                    <a href="#" wire:click.prevent="deleteID({{ $parent->id }})" class="text-red-600 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-red-600 hover:stroke-red-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('Delete') }}
                    </a>
                </div>
                @endif
            </h2>
            @if($items->get($parent->id))
            <ul role="list" class="mt-3 list-disc pl-5 space-y-3 text-slate-600">
                @foreach ($items->get($parent->id) as $item)
                <li wire:key="tag-{{ $item->id }}" class="group space-y-1 hover:text-sky-400 {{ $choice_id == $item->id ? 'text-sky-400' : '' }}">
                    <div wire:click.prevent="$emit('choiceTag', {{ $item->id }});" class="cursor-pointer">
                        <span>{{ $item->name }}</span>
                        @if ($item->materials_count > 0)
                        <span class="text-xs rounded-full px-2 py-1 bg-slate-100 font-semibold text-slate-700">{{ $item->materials_count }}</span>
                        @endif
                    </div>
                    @if($is_edit)
                    <div class="flex space-x-2 text-sm">
                        <a href="#" wire:click.prevent="edit({{ $item->id }})" class="text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-slate-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Edit') }}
                        </a>
                        <a href="#" wire:click.prevent="deleteID({{ $item->id }})" class="text-red-600 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-red-600 hover:stroke-red-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Delete') }}
                        </a>
                    </div>
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
    @if ($tag_id)
    <!-- Modal -->
    <div wire:ignore.self class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <form wire:submit.prevent="update">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block align-top sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-sky-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 stroke-sky-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    </div>
                    <div class="mt-3 w-full text-center sm:mt-0 sm:ml-4 sm:text-left space-y-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            {{ __('update tag') }}
                        </h3>
                        <div class="relative z-0 mb-6 w-full group">
                            <input wire:model.lazy="name" autocomplete="off" type="text" name="name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-sky-500 focus:outline-none focus:ring-0 focus:border-sky-600 peer" placeholder=" " required />
                            <label for="name" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-sky-600 peer-focus:dark:text-sky-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('Tag Name') }}</label>
                            @error('name')
                            <div class="text-xs text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="relative w-full group">
                            <label for="parent" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">{{ __('Parent Tag') }}</label>
                            <select wire:model.lazy="parent_id" id="parent" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-sky-500 dark:focus:border-sky-500">
                                <option value="">--</option>
                                @foreach ($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id')
                            <div class="text-xs text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-sky-600 text-base font-medium text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Update') }}
                </button>
                <button wire:click.prevent="resetTag" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Cancel') }}
                </button>
                </div>
            </div>
            </div>
        </form>
    </div>
    @endif
    @if ($delete_id)
    <!-- Modal -->
    <div wire:ignore.self class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                    {{ __('drop tag to the trash') }}
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
    @endif
</div>
