<div class="flex space-x-4">
    <div class="flex-none mt-2">{{ __('Copy Text') }}</div>
    <span x-text="foo"></span>
    <div class="flex-1 space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            <template x-for="(text, index) in texts" :key="'add_text_' + index">
                <div>
                    <div class="flex border-b">
                        <div class="flex flex-col flex-1">
                            <input x-model="texts[index]" x-on:paste="paste_rows($event, index)" type="text" name="texts[]" class="w-full border-0 focus:outline-none p-2 sm:text-sm border-gray-300 rounded-md" :placeholder="'{{ __('Copy ') }}' + (index + 1) + ' (`Ctrl + v` to paste rows)'" autocomplete="off">
                        </div>
                        <div x-on:click="removeInput(text)" class="flex-none flex items-center justify-end bg-white text-sm cursor-pointer mx-2">
                            <svg class="h-4 w-4 stroke-red-500 hover:stroke-red-700" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                    </div>
                </div>
            </template>
            <div :key="'keyfornew'">
                <div x-on:click="add_text" class="flex border border-dashed cursor-pointer rounded-md">
                    <div class="flex-1 cursor-pointer focus:outline-none p-2 bg-gray-100 text-gray-400 sm:text-sm">
                        {{ __('Add New One') }}
                    </div>
                    <div class="flex-none flex items-center justify-end text-sm bg-white cursor-pointer mx-2">
                        <svg class="h-4 w-4 stroke-main-500 hover:stroke-main-700" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
