<div class="overflow-x-auto sm:-mx-6 lg:-mx-8 flex-none">
    {{-- toastr --}}
    @include('livewire.toastr')
    @if ($material)
    <div class="flex flex-col py-2 min-w-max sm:px-6 lg:px-8 space-y-4">
        <spna></spna>
        <form wire:submit.prevent="save">
            <div class="flex flex-col border rounded-lg w-72 space-y-4 shadow-md">
                <div class="py-4 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                    {{ __('Material Edit') }}
                </div>
                <div class="relative z-0 mb-6 w-full group px-6">
                    <input wire:model.lazy="material.title" autocomplete="off" type="text" name="title" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-sky-500 focus:outline-none focus:ring-0 focus:border-sky-600 peer" placeholder=" " required />
                    <label for="title" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-sky-600 peer-focus:dark:text-sky-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('Material Title') }}</label>
                    @error('material.title')
                    <div class="text-xs text-red-600">{{ $message }}</div>
                    @enderror
                </div>
                <div class="relative px-6 space-y-4">
                    <label class="relative block">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                            <svg class="h-5 w-5 fill-slate-300" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sr-only">Tap tag</span>
                        <input wire:model.debounce.250ms="search" wire:keydown.enter.prevent="addTagBySearch" type="text" name="search" autocomplete="off" class="block bg-white w-full border border-slate-300 rounded-md py-2 pl-9 pr-3 shadow-sm placeholder:text-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm" placeholder="Tapping for add tag...">
                        @if ($autocompelte)
                        <div class="block z-20 bg-white w-full border border-slate-300 rounded-md absolute mt-1 divide-y">
                            @foreach ($autocompelte as $key)
                                <div wire:click.prevent="addTag('{{ $key }}')" class="p-2 px-4 first:rounded-t-md text-gray-600 bg-white hover:bg-gray-100 cursor-pointer">{{ $key }}</div>
                            @endforeach
                            <div class="p-2 px-4 first:rounded-t-md rounded-b-md text-gray-600 bg-gray-100 cursor-pointer">{{ __('or `enter` to add') }}</div>
                        </div>
                        @endif
                    </label>
                    @foreach ($keywords as $keyword)
                    <div class="flex items-center">
                        <input id="checkbox-{{ $keyword }}" wire:model="checkboxes.{{ $keyword }}" aria-describedby="checkbox-1" type="checkbox" class="w-4 h-4 text-sky-600 bg-gray-100 rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-{{ $keyword }}" class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $keyword }}</label>
                    </div>
                    @endforeach
                </div>
                <div class="bg-gray-50 rounded-b-lg px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-sky-600 text-base font-medium text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <svg wire:loading  wire:loading.attr="disabled" wire:target="save" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('Update') }}
                    </button>
                    <button wire:click.prevent="resetMaterial" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endif
</div>
