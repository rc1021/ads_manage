@extends('layouts.app')

@section('content')
<div class="flex flex-col space-y-6">
    <form class="flex flex-col mx-auto" enctype="multipart/form-data" method="post" action="{{ route('videos.store') }}">
        <div class="max-w-4xl">
            <div class="flex flex-col max-w-sm mx-auto bg-white rounded-lg shadow-lg mt-5 p-4 gap-4">
                @csrf
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span>影片上傳</span>
                </div>
                <label class="block">
                    <span class="sr-only">Choose video</span>
                    <input class="block w-full text-sm text-slate-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-main-50 file:text-main-700
                        hover:file:bg-main-100
                    "
                    name="video"
                    type="file"
                    accept="video/*" />
                </label>
                @error('video')
                    <div class="text-center text-main-500">{{ $message }}</div>
                @enderror
                <button class="bg-main-50 text-main-700 rounded-lg border-1 border-main-50 hover:bg-main-100 p-3 py-2">
                    送出
                </button>
            </div>
        </div>
    </form>
    <div class="flex space-x-4 text-sm text-gray-500 gap-2 mx-auto">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-yellow-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            快照中
        </div>
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-yellow-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
            </svg>
            製作高斯模糊、PAD影片中
        </div>
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-yellow-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            製作影片串流中
        </div>
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-red-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            上傳影片過程有錯誤發生
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 gap-y-10 p-4">
        @foreach ($items as $item)
        <div class="relative">
            <a href="{{ route('videos.show', $item->id) }}" class="block max-w-xs mx-auto h-80 md:h-56 md:shrink-0 {{ ($item->converted_for_thumbing_at) ?: 'rounded-lg shadow-lg bg-gray-200 bg-stripes bg-stripes-white' }}">
                {{--  main picture  --}}
                @if ($item->converted_for_thumbing_at)
                <img class="rounded-lg shadow-lg transition-all hover:scale-105 inset-10 object-cover w-full h-full" src="{{ $item->thumbnail_url }}" />
                @endif
            </a>
            <div class="absolute z-10 w-full -bottom-6">
                <div class="border rounded-lg shadow-lg bg-white w-72 md:w-52 mx-auto p-3">
                    <a href="{{ route('videos.show', $item->id) }}" class="block truncate text-sm">{{ $item->original_name }}</a>
                    <div class="flex justify-between text-sm text-slate-400 ">
                        <div class="flex gap-2">
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
                        </div>
                        <div class="flex items-end">
                            @if (!is_null($item->error))
                            <a href="{{ route('videos.show', $item->id) }}" title="{{ $item->error }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="hover:scale-125 transition-all h-5 w-5 stroke-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </a>
                            @elseif (!$item->converted_for_thumbing_at)
                            <svg xmlns="http://www.w3.org/2000/svg" alt="{{ __('thumbnailing') }}..." class="animate-bounce h-5 w-5 stroke-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            @elseif (!$item->converted_for_downloading_at)
                            <svg xmlns="http://www.w3.org/2000/svg" alt="{{ __('downloading') }}..." class="animate-bounce h-5 w-5 stroke-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                            </svg>
                            @elseif (!$item->converted_for_streaming_at)
                            <svg xmlns="http://www.w3.org/2000/svg" alt="{{ __('streaming') }}..." class="animate-bounce h-5 w-5 stroke-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
