<nav aria-label="Breadcrumb">
    <ol class="inline-flex items-center py-1  space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('materials.index') }}" class="inline-flex items-center text-lg text-gray-700 hover:text-gray-900">
            <svg class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
            {{ __('Materials') }}
            </a>
        </li>
        @if(request()->input('tid') == -1)
        <li>
            <span class="flex items-center">
            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
            <span class="ml-1 text-lg font-medium text-gray-700 hover:text-gray-900 md:ml-2">{{ __('Material Trash') }}</span>
            </span>
        </li>
        @elseif($tag)
        @if ($parent = data_get($tag, 'parent', null))
        <li>
            <a href="#" class="flex items-center">
            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
            <span class="ml-1 text-lg font-medium text-gray-700 hover:text-gray-900 md:ml-2">{{ data_get($parent, 'name') }}</span>
            </a>
        </li>
        @endif
        <li aria-current="page">
            <div class="flex items-center">
            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
            <span class="ml-1 text-lg font-medium text-gray-400 md:ml-2">{{ $tag->name }}</span>
            </div>
        </li>
        @endif
    </ol>
</nav>
