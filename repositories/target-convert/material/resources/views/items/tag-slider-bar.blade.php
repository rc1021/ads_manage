<div x-data="materials_tagSliderBar()" class="z-20 hidden lg:block fixed inset-0 top-0 left-0 w-[19.5rem] pb-10 px-8 space-y-3 overflow-y-auto">
    @php
        $tmp_query = array_filter($query, fn ($val, $key) => !in_array($key, ['tid', 'page']), ARRAY_FILTER_USE_BOTH)
    @endphp
    <nav class="lg:text-sm lg:leading-6 relative">
        <div class="sticky z-20 top-0 -ml-0.5">
            <div class="bg-white space-y-3 pt-6">
                <label class="relative block">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                    <svg class="h-5 w-5 fill-slate-300" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                    </svg>
                    </span>
                    <span class="sr-only">Search</span>
                    <input type="text" onmouseenter="this.focus(); this.select();" x-model="search" name="search" autocomplete="off" class="block bg-white w-full border border-slate-300 rounded-md py-2 pl-9 pr-3 shadow-sm placeholder:text-slate-400 focus:outline-none focus:border-main-500 focus:ring-main-500 focus:ring-1 sm:text-sm" placeholder="Search for tag...">
                </label>
                <div class="bg-white flex space-x-2 px-4 items-center text-base font-semibold text-slate-900">
                        <a href="{{ route('material.items.index', array_merge($query, ['act' => 'tag-edit'])) }}" class="">
                            <span>管理標籤</span>
                        </a>
                        @if($act == 'tag-edit')
                        <div class="text-sm items-center flex">
                            <a x-on:click.prevent="create_folder" href="#" title="{{ __('新增資料夾') }}" class="inline-flex ml-1 text-main-700 bg-main-100 hover:bg-main-200 rounded-full ">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-flex" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </a>
                            <a href="{{ route('material.items.index', array_merge($query, ['act' => ''])) }}" title="{{ __('完成編輯') }}" class="inline-flex ml-2 text-main-700 bg-main-100 hover:bg-main-200 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-flex" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </a>
                        </div>
                        @endif
                </div>
                <hr>
            </div>
            <div class="h-8 bg-gradient-to-b from-white"></div>
        </div>
        <ul class="space-y-3 px-4">
            <li>
                <a href="{{ route('material.items.index', $tmp_query) }}" class="block">
                    <h2 class="text-base font-semibold text-slate-900 hover:text-main-400 flex items-center space-x-1 {{ ($tag_id > 0 || $is_trashed) ?: 'text-main-400' }}">
                        {{ __('All Materials') }}
                    </h2>
                </a>
            </li>
            @foreach ($tag_parents as $parent)
            <li class="flex flex-col">
                <a href="#" class="block text-slate-900">
                    <h2 class="text-base font-semibold group flex items-center">
                        {{ $parent->name }}
                    </h2>
                    @if($act == 'tag-edit' && $parent->id > 0)
                    <div class="flex space-x-2 text-sm">
                        <a x-on:click.prevent="update_folder({{ $parent->toJson() }})" href="#" class="text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-slate-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Edit') }}
                        </a>
                        <a x-on:click.prevent="drop_folder({{ $parent->toJson() }})" href="#" class="text-red-600 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-red-600 hover:stroke-red-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            {{ __('Delete') }}
                        </a>
                    </div>
                    @endif
                </a>
                @if($tags->get($parent->id))
                <ul role="list" class="mt-3 list-disc pl-5 space-y-3 text-slate-700 max-h-30 max-h-[10.5rem] overflow-y-auto relative">
                    @foreach ($tags->get($parent->id) as $item)
                    <li x-show="!search || !search || '{{ $item->name }}'.includes(search)" class="group space-y-1 hover:text-main-400 {{ $tag_id == $item->id ? 'text-main-400' : '' }}">
                        <a href="{{ route('material.items.index', array_merge($tmp_query, ['tid' => $item->id])) }}">
                            <span>{{ $item->name }}</span>
                            @if ($item->materials_count > 0)
                            <span class="text-xs rounded-full px-2 py-1 bg-slate-100 font-semibold text-slate-700">{{ $item->materials_count }}</span>
                            @endif
                        </a>
                        @if($act == 'tag-edit')
                        <div class="flex space-x-2 text-sm">
                            <a x-on:click.prevent="edit({{ $item->toJson() }})" href="#" class="text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-slate-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Edit') }}
                            </a>
                            <a x-on:click.prevent="drop({{ $item->toJson() }})" href="#" class="text-red-600 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-red-600 hover:stroke-red-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                {{ __('Delete') }}
                            </a>
                        </div>
                        @endif
                    </li>
                    @endforeach
                    @if(count($tags->get($parent->id)) > 5 || ($act == 'tag-edit' && count($tags->get($parent->id)) > 3))
                    <div class="h-6 bg-gradient-to-t from-white sticky bottom-0 -ml-5 pointer-events-none"></div>
                    @endif
                </ul>
                @else
                <div class="mt-3 pl-5 space-y-3 text-slate-300">{{ __('empty') }}</div>
                @endif
            </li>
            @endforeach
            <li>
                <a href="{{ route('material.items.index', array_merge($query, ['tid' => -1])) }}" class="block text-slate-900 hover:text-main-400 {{ request()->input('tid') == -1 ? 'text-main-400' : '' }}">
                    <h2 class="text-base font-semibold flex items-center space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        {{ __('Material Trash') }}
                    </h2>
                </a>
            </li>
        </ul>
    </nav>
    <!-- Modal -->
    <div x-show="drop_folder_url" style="display: none;" class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <form method="POST" :action="drop_folder_url">
            @csrf
            @method('DELETE')
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-main-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        {{ __('drop folder to the trash') }}
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                        {{ __('Are you sure if you put it in the trash?') }}
                        </p>
                        <p class="text-sm text-gray-500">
                        {{ __('Folder name') }}: <span x-text="name" class="text-red-600"></span>
                        </p>
                    </div>
                    </div>
                </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Drop') }}
                </button>
                <button x-on:click.prevent="reset" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Cancel') }}
                </button>
                </div>
            </div>
            </div>
        </form>
    </div>
    <!-- Modal -->
    <div x-show="create_folder_url" style="display: none;" class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <form method="POST" :action="create_folder_url">
            @csrf
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block align-top sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-main-100 hover:bg-main-200 sm:mx-0 sm:h-10 sm:w-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-main-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        </div>
                        <div class="mt-3 w-full text-center sm:mt-0 sm:ml-4 sm:text-left space-y-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ __('New Folder') }}
                            </h3>
                            <div class="relative z-0 mb-6 w-full group">
                                <input id="name" name="name" autocomplete="off" type="text" name="name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-main-600 peer" placeholder=" " required />
                                <label for="name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-main-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('Folder Name') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-main-600 text-base font-medium text-white hover:bg-main-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-500 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Create') }}
                </button>
                <button x-on:click.prevent="reset" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Cancel') }}
                </button>
                </div>
            </div>
            </div>
        </form>
    </div>
    <!-- Modal -->
    <div x-show="update_folder_url" style="display: none;" class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <form method="POST" :action="update_folder_url">
            @csrf
            @method('PUT')
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block align-top sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-main-100 hover:bg-main-200 sm:mx-0 sm:h-10 sm:w-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-main-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </div>
                        <div class="mt-3 w-full text-center sm:mt-0 sm:ml-4 sm:text-left space-y-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ __('update folder') }}
                            </h3>
                            <div class="relative z-0 mb-6 w-full group">
                                <input x-model="name" autocomplete="off" type="text" name="name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-main-600 peer" placeholder=" " required />
                                <label for="name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-main-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('Folder Name') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-main-600 text-base font-medium text-white hover:bg-main-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-500 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Update') }}
                </button>
                <button x-on:click.prevent="reset" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Cancel') }}
                </button>
                </div>
            </div>
            </div>
        </form>
    </div>
    <!-- Modal -->
    <div x-show="update_url" style="display: none;" class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <form method="POST" :action="update_url">
            @csrf
            @method('PUT')
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block align-top sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-main-100 hover:bg-main-200 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 stroke-main-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        </div>
                        <div class="mt-3 w-full text-center sm:mt-0 sm:ml-4 sm:text-left space-y-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ __('update tag') }}
                            </h3>
                            <div class="relative z-0 mb-6 w-full group">
                                <input x-model="folder_id" type="hidden" name="folder_id" />
                                <input x-model="name" autocomplete="off" type="text" name="name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-main-600 peer" placeholder=" " required />
                                <label for="name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-main-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('Tag Name') }}</label>
                            </div>
                            <div class="relative w-full group">
                                <label for="parent" class="block mb-2 text-sm font-medium text-gray-900">{{ __('Folder Name') }}</label>
                                <select x-model="folder_id" name="folder_id" id="parent" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-main-500 focus:border-main-500 block w-full p-2.5">
                                    <option value="0">--</option>
                                    @foreach ($tag_parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-main-600 text-base font-medium text-white hover:bg-main-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-500 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Update') }}
                </button>
                <button x-on:click.prevent="reset" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Cancel') }}
                </button>
                </div>
            </div>
            </div>
        </form>
    </div>
    <!-- Modal -->
    <div x-show="drop_url" style="display: none;" class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <form method="POST" :action="drop_url">
            @csrf
            @method('DELETE')
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        {{ __('drop tag to the trash') }}
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                        {{ __('Are you sure if you put it in the trash?') }}
                        </p>
                        <p class="text-sm text-gray-500">
                        {{ __('tag name') }}: <span x-text="name" class="text-red-600"></span>
                        </p>
                    </div>
                    </div>
                </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Drop') }}
                </button>
                <button x-on:click.prevent="reset" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Cancel') }}
                </button>
                </div>
            </div>
            </div>
        </form>
    </div>
</div>
<script>
    function materials_tagSliderBar () {
        return {
            search: null,
            name: null,
            id: null,
            folder_id: null,
            update_url: null,
            drop_url: null,
            create_folder_url: null,
            update_folder_url: null,
            drop_folder_url: null,
            drop(item) {
                this.id = item.id;
                this.name = item.name;
                this.folder_id = item.folder_id;
                this.drop_url = '{{ route('material.tags.destroy', 'ref-id') }}'.replace('ref-id', item.id);
            },
            edit(item) {
                this.id = item.id;
                this.name = item.name;
                this.folder_id = item.folder_id;
                this.update_url = '{{ route('material.tags.update', 'ref-id') }}'.replace('ref-id', item.id);
            },
            create_folder() {
                this.create_folder_url = '{{ route('material.tag.folders.store') }}';
            },
            update_folder(item) {
                this.id = item.id;
                this.name = item.name;
                this.update_folder_url = '{{ route('material.tag.folders.update', 'ref-id') }}'.replace('ref-id', item.id);
            },
            drop_folder(item) {
                this.id = item.id;
                this.name = item.name;
                this.drop_folder_url = '{{ route('material.tag.folders.destroy', 'ref-id') }}'.replace('ref-id', item.id);
            },
            reset() {
                this.create_folder_url = null;
                this.update_folder_url = null;
                this.drop_folder_url = null;
                this.update_url = null;
                this.drop_url = null;
            }
        }
    }
</script>
