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
