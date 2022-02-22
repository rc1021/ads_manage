<div class="bg-white flex">
    <nav class="flex mr-auto">
        <label for="videotype_fileupload" x-bind:class="{ 'border-b-2 font-medium border-main-500 text-main-500': videotype == 'fileupload', 'text-gray-600': videotype != 'fileupload' }" class="py-4 px-6 block hover:text-main-500 focus:outline-none">
            上傳檔案
            <input x-model="videotype" type="radio" id="videotype_fileupload" value="fileupload" class="invisible" />
        </label>
        <label for="videotype_url" x-bind:class="{ 'border-b-2 font-medium border-main-500 text-main-500': videotype == 'url', 'text-gray-600': videotype != 'url' }" class="py-4 px-6 block hover:text-main-500 focus:outline-none">
            Youtube 網址
            <input x-model="videotype" type="radio" id="videotype_url" value="url" class="invisible" />
        </label>
    </nav>
</div>
<div class="pt-5">
    <div x-show="videotype == 'fileupload'" class="">
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
    </div>
    <div x-show="videotype == 'url'" class="">
        <textarea name="urls" class="w-full h-48 max-h-72 border-slate-200  hover:border-slate-400 p-2 outline-0 border-4 rounded-lg" placeholder="{{ __('請輸入影片網址，每行一個有效網址') }}"></textarea>
    </div>
</div>
