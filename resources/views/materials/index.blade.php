@extends('layouts.app')

@php
    // - Default -
    $query = request()->query();
    $act = data_get($query, 'act');
    $tag_id = data_get($tag, 'id', 0);
    $type_str = strtolower(\App\Enums\MaterialType::getKey((int)$type) ?: 'text');
@endphp

@section('content')
    @include('materials.toastr')
    <div class="flex">
        <div class="flex-none min-w-max max-w-2xl">
            @include('materials.tag-slider-bar')
        </div>
        <div class="flex-1 p-6">
            @include('materials.breadcrumbs')
            @include('materials.toolbar')
            @include('materials.index.' . $type_str)
        </div>
    </div>
@endsection
