<div id="drop_zone_images"
    class="flex flex-col relative divide-y rounded-lg cursor-pointer p-1 h-48 max-h-72 overflow-hidden overflow-y-auto border-4 border-dashed border-slate-200  hover:border-slate-400 after:content-[attr(title)] after:text-slate-400 after:flex after:items-center after:align-middle after:justify-center after:w-full after:h-full after:absolute after:font-semibold beofre:text-4xl"
    title="{{ __('Drag one or more video files to this Drop Zone ... (or click)') }}"
    accept="video/*"
    data-rel="drop-uploader"
    data-type="{{ \App\Enums\MaterialType::Video }}"
    data-temporary-url="{{ route('snowflake.store') }}"
    data-url="{{ route('materials.upload') }}"
    data-size-limit="{{ 30 * 1024 * 1024 }}">
    {{-- drop items --}}
</div>
<div class="relative flex py-5 items-center">
    <div class="flex-grow border-t border-slate-200"></div>
    <span class="flex-shrink mx-4 text-slate-400">{{ __('or') }}</span>
    <div class="flex-grow border-t border-slate-200"></div>
</div>
<div class="flex items-center space-x-4">
    <span>{{ __('Urls') }}</span>
    <textarea rows="1" class="flex-1 min-h-max border-slate-200 hover:border-slate-400 outline-0 p-2 border rounded-lg"></textarea>
    <button type="button" class="flex-none mt-3 p-2 rounded-md border border-gray-300 shadow-sm bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
        {{ __('Upload') }}
    </button>
</div>
