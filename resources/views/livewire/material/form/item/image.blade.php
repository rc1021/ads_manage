<div id="drop_zone_images"
    class="flex relative rounded-lg z-0 h-48 max-h-72 border-4 border-dashed border-slate-200  hover:border-slate-400 before:content-[attr(before)] before:text-slate-400 before:flex before:items-center before:align-middle before:justify-center before:w-full before:font-semibold beofre:text-4xl"
    before="{{ __('Drag one or more files to this Drop Zone ...') }}"
    accept="image/*"
    data-rel="drop-part-upload"
    data-type="{{ \App\Enums\MaterialType::Image }}"
    data-temporary-url="{{ route('snowflake.store') }}"
    data-url="{{ route('materials.upload') }}"
    data-size-limit="{{ 1 * 1024 * 1024 }}">

</div>
