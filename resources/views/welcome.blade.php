<x-layout>
    <div class="max-w-sm mx-auto bg-white shadow-lg mt-5 py-8 px-6">
        <div class="text-center">圖片上傳</div>
        <form class="flex items-center space-x-6" action="{{ route('materials.store') }}">
            @csrf
            <label class="block">
            <span class="sr-only">Choose profile photo</span>
            <input class="block w-full text-sm text-slate-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-violet-50 file:text-violet-700
                hover:file:bg-violet-100
            "
            type="file"
            multiple="multiple"
            accept="image/*"
            data-rel="part-upload"
            data-type="{{ \App\Enums\MaterialType::Image }}"
            data-url="{{ route('materials.upload') }}" />
            </label>
        </form>
    </div>
    <div class="max-w-sm mx-auto bg-white shadow-lg mt-5 py-8 px-6">
        <div class="text-center">影片上傳</div>
        <form class="flex items-center space-x-6" action="{{ route('materials.store') }}">
            @csrf
            <label class="block">
            <span class="sr-only">Choose profile photo</span>
            <input class="block w-full text-sm text-slate-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-violet-50 file:text-violet-700
                hover:file:bg-violet-100
            "
            type="file"
            multiple="multiple"
            accept="video/*"
            data-rel="part-upload"
            data-type="{{ \App\Enums\MaterialType::Video }}"
            data-url="{{ route('materials.upload') }}" />
            </label>
        </form>
    </div>
</x-layout>
