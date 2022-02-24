@extends('materials::layouts.app')

@php
    // - Default -
    $query = request()->query();
    unset($query['search']);
    unset($query['page']);
    $act = data_get($query, 'act');
    $tag_id = data_get($tag, 'id', 0);
    $is_trashed = data_get($query, 'tid', 0) == -1;
    $type_str = strtolower(\TargetConvert\Material\Enums\MaterialType::getKey((int)$type) ?: 'text');
@endphp

@section('content')
    @include('materials::partials.toastr')
    <div class="relative">
        @include('materials::items.tag-slider-bar')
        <div class="lg:pl-[19.5rem] p-6 relative">
            @include('materials::items.breadcrumbs')
            @include('materials::items.toolbar')
            @include('materials::items.content')
        </div>
    </div>
@endsection
