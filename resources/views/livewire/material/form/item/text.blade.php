<div class="flex space-x-4">
    <div class="flex-none">{{ __('Copy Text') }}</div>
    <div class="flex-1 space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($texts as $key => $text)
            <div>
                <div class="flex border-b">
                    <div class="flex flex-col flex-1">
                        <input type="text" id="text_{{$key}}" wire:model="texts.{{$key}}" class="w-full border-0 focus:outline-none p-2 pb-1 sm:text-sm border-gray-300 rounded-md" placeholder="{{ __('Copy '.($key+1)) }}" autocomplete="off">
                    </div>
                    <div wire:click="removeInput({{$key}})" class="flex-none flex items-center justify-end bg-white text-sm cursor-pointer mx-2">
                        <svg class="h-4 w-4 stroke-red-500 hover:stroke-red-700" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                </div>
                @error('texts.'.$key)
                <span class="text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>
            @endforeach
            <div wire:click="addInput" wire:key="keyfornew">
                <div class="flex border border-dashed cursor-pointer rounded-md">
                    <div class="flex-1 cursor-pointer focus:outline-none p-2 bg-gray-100 text-gray-400 sm:text-sm">
                        {{ __('Add New One') }}
                    </div>
                    <div class="flex-none flex items-center justify-end text-sm bg-white cursor-pointer mx-2">
                        <svg class="h-4 w-4 stroke-sky-500 hover:stroke-sky-700" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
