@extends('layouts.app')

@section('content')
    <div class="flex">
        <div class="min-w-max max-w-2xl">
            <livewire:material.tag-slider-bar />
        </div>
        <div class="p-6">
            <livewire:material.breadcrumbs />
            <livewire:material.toolbar/>
            <livewire:material.data-list />
        </div>
    </div>
@endsection
