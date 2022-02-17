<div class="flex items-center space-x-4">
    <span>{{ __('Add Type') }}</span>
    <div class="inline-flex rounded-md shadow-sm" role="group">
        <label x-bind:class="{ 'bg-white ring-slate-900/5': type != 1, 'bg-main-500 ring-main-500': type == 1 }" for="material_form_item_type_1" type="button" class="group inline-flex items-center rounded-l-lg p-3 py-2 ring-1 shadow space-y-3 hover:bg-main-500 hover:ring-main-500">
            <div class="flex items-center space-x-1">
                <svg x-bind:class="{ 'stroke-main-500': type != 1, 'stroke-white': type == 1 }" class="h-5 w-5 group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                <span x-bind:class="{ 'text-slate-900': type != 1, 'text-white': type == 1 }" class="hidden lg:inline-block text-sm font-semibold group-hover:text-white">{{ __('Text') }}</span>
                <input x-model="type" name="type" id="material_form_item_type_1" type="radio" value="1" class="hidden" />
            </div>
        </label>
        <label x-bind:class="{ 'bg-white ring-slate-900/5': type != 2, 'bg-main-500 ring-main-500': type == 2 }" for="material_form_item_type_2" type="button" class="group inline-flex items-center p-3 py-2 ring-1 shadow space-y-3 hover:bg-main-500 hover:ring-main-500">
            <div class="flex items-center space-x-1">
                <svg x-bind:class="{ 'stroke-main-500': type != 2, 'stroke-white': type == 2 }" class="h-5 w-5 group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span x-bind:class="{ 'text-slate-900': type != 2, 'text-white': type == 2 }" class="hidden lg:inline-block text-sm font-semibold group-hover:text-white">{{ __('Image') }}</span>
                <input x-model="type" name="type" id="material_form_item_type_2" type="radio" value="2" class="hidden" />
            </div>
        </label>
        <label x-bind:class="{ 'bg-white ring-slate-900/5': type != 8, 'bg-main-500 ring-main-500': type == 8 }" for="material_form_item_type_8" type="button" class="group inline-flex items-center rounded-r-lg p-3 py-2 ring-1 shadow space-y-3 hover:bg-main-500 hover:ring-main-500">
            <div class="flex items-center space-x-1">
                <svg x-bind:class="{ 'stroke-main-500': type != 8, 'stroke-white': type == 8 }" class="h-5 w-5 group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                </svg>
                <span x-bind:class="{'text-slate-900': type != 8, 'text-white': type == 8 }" class="hidden lg:inline-block text-sm font-semibold group-hover:text-white">{{ __('Video') }}</span>
                <input x-model="type" name="type" id="material_form_item_type_8" type="radio" value="8" class="hidden" />
            </div>
        </label>
    </div>
</div>
