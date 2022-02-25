@if($error = session()->get('error'))
    <div class="bg-red-100 border-t border-b border-red-500 text-red-700 px-4 py-3 my-4" role="alert">
        <p class="font-bold">{{ \Illuminate\Support\Arr::get($error->get('title'), 0) }}</p>
        <p class="text-sm">{!!  \Illuminate\Support\Arr::get($error->get('message'), 0) !!}</p>
    </div>
@elseif ($errors = session()->get('errors'))
    @if ($errors->hasBag('error'))
    <div class="bg-red-100 border-t border-b border-red-500 text-red-700 px-4 py-3 my-4" role="alert">
        @foreach($errors->getBag("error")->toArray() as $message)
        <p class="text-sm">{!!  \Illuminate\Support\Arr::get($message, 0) !!}</p>
        @endforeach
    </div>
    @endif
    @if ($errors->hasBag('default'))
    <div class="bg-red-100 border-t border-b border-red-500 text-red-700 px-4 py-3 my-4" role="alert">
        @foreach($errors->getBag("default")->toArray() as $message)
        <p class="text-sm">{!!  \Illuminate\Support\Arr::get($message, 0) !!}</p>
        @endforeach
    </div>
    @endif
@endif

@if($success = session()->get('success'))
    <div class="bg-emerald-100 border-t border-b border-emerald-500 text-emerald-700 px-4 py-3 my-4" role="alert">
        <p class="font-bold">{{ \Illuminate\Support\Arr::get($success->get('title'), 0) }}</p>
        <p class="text-sm">{!!  \Illuminate\Support\Arr::get($success->get('message'), 0) !!}</p>
    </div>
@endif

@if($info = session()->get('info'))
    <div class="bg-sky-100 border-t border-b border-sky-500 text-sky-700 px-4 py-3 my-4" role="alert">
        <p class="font-bold">{{ \Illuminate\Support\Arr::get($info->get('title'), 0) }}</p>
        <p class="text-sm">{!!  \Illuminate\Support\Arr::get($info->get('message'), 0) !!}</p>
    </div>
@endif

@if($warning = session()->get('warning'))
    <div class="bg-amber-100 border-t border-b border-amber-500 text-amber-700 px-4 py-3 my-4" role="alert">
        <p class="font-bold">{{ \Illuminate\Support\Arr::get($warning->get('title'), 0) }}</p>
        <p class="text-sm">{!!  \Illuminate\Support\Arr::get($warning->get('message'), 0) !!}</p>
    </div>
@endif
