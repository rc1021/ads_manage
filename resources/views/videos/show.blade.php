@extends('layouts.app')

@section('content')
<div class="space-y-6 py-6">
    <div x-data="{tab: 0}">
        <div class="bg-white flex">
            <nav class="flex mx-auto">
                <button x-on:click="tab = 0" x-bind:class="{ 'border-b-2 font-medium border-main-500 text-main-500': tab == 0, 'text-gray-600': tab != 0 }" class="py-4 px-6 block hover:text-main-500 focus:outline-none">
                    快照
                </button>
                <button x-on:click="tab = 1" x-bind:class="{ 'border-b-2 font-medium border-main-500 text-main-500': tab == 1, 'text-gray-600': tab != 1 }" class="py-4 px-6 block hover:text-main-500 focus:outline-none">
                    動畫(6秒)
                </button>
                <button x-on:click="tab = 2" x-bind:class="{ 'border-b-2 font-medium border-main-500 text-main-500': tab == 2, 'text-gray-600': tab != 2 }" class="py-4 px-6 block hover:text-main-500 focus:outline-none">
                    黑底串流
                </button>
                <button x-on:click="tab = 3" x-bind:class="{ 'border-b-2 font-medium border-main-500 text-main-500': tab == 3, 'text-gray-600': tab != 3 }" class="py-4 px-6 block hover:text-main-500 focus:outline-none">
                    高斯模糊串流
                </button>
            </nav>
        </div>
        <div class="p-10">
            @if (!is_null($item->error))
            <div class="max-w-sm mx-auto gap-2">
                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" alt="{{ $item->error }}" class="flex-none h-10 w-10 stroke-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="flex-1 text-left">{{ $item->error }}</p>
                </div>
                <a href="{{ route('videos.redo', $item->id) }}" class="block text-center mt-10 rounded-lg shadow-lg border-0 bg-main-600 text-main-50 hover:text-main-100 mx-auto p-3">
                    {{ __('Redo') }}
                </a>
            </div>
            @else
            <div x-show="tab == 0" class="flex max-w-sm mx-auto">
                @if (!$item->converted_for_thumbing_at)
                <div class="flex mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="animate-bounce h-5 w-5 stroke-yellow-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ __('thumbnailing') }}...</span>
                </div>
                @else
                <div class="relative flex items-center justify-center rounded-lg overflow-hidden w-56 sm:w-96">
                    <div class="absolute inset-0 opacity-50 bg-gray-200 bg-stripes bg-stripes-white"></div>
                    <img src="{{ $item->thumbnail_url }}" class="relative z-10 max-h-48 bg-contain bg-no-repeat bg-center sm:bg-top" />
                </div>
                @endif
            </div>
            <div x-show="tab == 1" class="flex max-w-sm mx-auto">
                @if (!$item->converted_for_thumbing_at)
                <div class="flex mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="animate-bounce h-5 w-5 stroke-yellow-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ __('thumbnailing') }}...</span>
                </div>
                @else
                <div class="relative flex items-center justify-center rounded-lg overflow-hidden w-56 sm:w-96">
                    <div class="absolute inset-0 opacity-50 bg-gray-200 bg-stripes bg-stripes-white"></div>
                    <img src="{{ $item->thumbnail_gif_url }}" class="relative z-10 max-h-48 bg-contain bg-no-repeat bg-center sm:bg-top" />
                </div>
                @endif
            </div>
            <div x-show="tab == 2" class="flex max-w-sm mx-auto">
                @if (!$item->converted_for_streaming_at)
                <div class="flex mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="animate-bounce h-5 w-5 stroke-yellow-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                    </svg>
                    <span>{{ __('downloading') }}...</span>
                </div>
                @else
                <video id="video" controls data-playlist="/videos/playlist/{{ $item->m3u8_pad_path }}"></video>
                @endif
            </div>
            <div x-show="tab == 3" class="flex max-w-sm mx-auto">
                @if (!$item->converted_for_streaming_at)
                <div class="flex mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="animate-bounce h-5 w-5 stroke-yellow-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>{{ __('streaming') }}...</span>
                </div>
                @else
                <video id="video" controls data-playlist="/videos/playlist/{{ $item->m3u8_gblur_path }}"></video>
                @endif
            </div>
            @endif
        </div>
    </div>
    <div class="flex flex-col">
        <h1 class="text-lg mx-auto">{{ $item->title }}</h1>
        <div class="flex text-sm text-slate-400 mx-auto mt-6 gap-2">
            {{-- seconds long --}}
            @if ($seconds = data_get($item, 'time', false))
            <div class="items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-slate-400 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                @if ($seconds > 3600)
                <span>{{ gmdate('H:i:s', $seconds) }}</span>
                @else
                <span>{{ gmdate('i:s', $seconds) }}</span>
                @endif
            </div>
            @endif
            {{-- disk size --}}
            @if ($size = data_get($item, 'size', false))
            <div class="items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-slate-400 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                @if (strlen(floor($size)) > 6)
                <span>{{ number_format($size / 1024 / 1024) }}MB</span>
                @else
                <span>{{ number_format($size / 1024) }}KB</span>
                @endif
            </div>
            @endif
            <div class="items-center">
                <a href="{{ route('videos.redo', $item->id) }}" class="text-center rounded-lg shadow-lg border-0 bg-main-600 text-main-50 hover:text-main-100 p-1 px-2">
                    {{ __('Redo') }}
                </a>
            </div>
        </div>
        <a href="{{ route('videos.index') }}" class="group flex mt-10 rounded-lg shadow-lg border-0 bg-main-50 hover:bg-main-100 text-main-600 hover:text-main-700 mx-auto p-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-main-600 group-hover:stroke-main-600 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
            </svg>
            <span>{{ __('back list') }}</span>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
    window.addEventListener("load", function(event) {
        var arr_video = document.getElementsByTagName('video');
        for (let video of arr_video) {
            if(Hls.isSupported())
            {
                var hls = new Hls();
                hls.loadSource(video.dataset.playlist);
                hls.attachMedia(video);
                hls.on(Hls.Events.MANIFEST_PARSED,function()
                {
                    video.play();
                });
            }
            else if (video.canPlayType('application/vnd.apple.mpegurl'))
            {
                video.src = video.dataset.playlist;
                video.addEventListener('canplay',function()
                {
                    video.play();
                });
            }
        }
    });
</script>
@endsection
