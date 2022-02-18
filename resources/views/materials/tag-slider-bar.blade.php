<div x-data="materials_tagSliderBar()" class="block max-w-xs mx-auto p-6 bg-whitering-1 ring-slate-900/5 space-y-3">
    <label class="relative block">
        <span class="absolute inset-y-0 left-0 flex items-center pl-2">
        <svg class="h-5 w-5 fill-slate-300" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
        </svg>
        </span>
        <span class="sr-only">Search</span>
        <input type="text" onmouseenter="this.focus(); this.select();" x-model="search" name="search" autocomplete="off" class="block bg-white w-full border border-slate-300 rounded-md py-2 pl-9 pr-3 shadow-sm placeholder:text-slate-400 focus:outline-none focus:border-main-500 focus:ring-main-500 focus:ring-1 sm:text-sm" placeholder="Search for tag...">
    </label>
    <div class="bg-white px-4 space-y-3">
        <a href="{{ route('materials.index', array_merge($query, ['act' => ($act == 'tag-edit') ? '' : 'tag-edit'])) }}" class="items-center text-base font-semibold text-slate-900 dark:text-slate-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            <span>管理標籤</span>
            @if($act == 'tag-edit')
            <span class="inline-flex items-center p-1 mr-2 text-sm font-semibold text-main-800 bg-main-100 rounded-full dark:bg-main-200 dark:text-main-800">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
            </span>
            @endif
        </a>
    </div>
    <hr>
    @php
        $tmp_query = array_filter($query, function($v, $k) {
            return $k != 'tid'
                || $k != 'page';
        }, ARRAY_FILTER_USE_BOTH);
    @endphp
    <div class="bg-white px-4 space-y-3">
        <a href="{{ route('materials.index', $tmp_query) }}" class="block">
            <h2 class="text-base font-semibold text-slate-900 hover:text-main-400 dark:text-slate-200 flex items-center space-x-1 {{ ($tag_id > 0 || $is_trashed) ?: 'text-main-400' }}">
                {{ __('All Materials') }}
            </h2>
        </a>
        @foreach ($tag_parents as $parent)
            <a href="{{ route('materials.index', array_merge($tmp_query, ['tid' => $parent->id])) }}" class="block text-slate-900 dark:text-slate-200 {{ $tag_id == $parent->id ? 'text-main-400' : '' }}">
                <h2 class="text-base font-semibold group">
                    {{ $parent->name }}
                    @if ($parent->materials_count > 0)
                    <span class="text-xs rounded-full px-2 py-1 bg-slate-100 font-semibold text-slate-700">{{ $parent->materials_count }}</span>
                    @endif
                    @if($act == 'tag-edit')
                    <div class="flex space-x-2 font-normal text-sm">
                        <a x-on:click.prevent="edit({{ $parent->toJson() }})" href="#" class="text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-slate-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Edit') }}
                        </a>
                        <a x-on:click.prevent="drop({{ $parent->toJson() }})" href="#" class="text-red-600 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-red-600 hover:stroke-red-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            {{ __('Delete') }}
                        </a>
                    </div>
                    @endif
                </h2>
            </a>
            @if($tags->get($parent->id))
            <ul role="list" class="mt-3 list-disc pl-5 space-y-3 text-slate-600">
                @foreach ($tags->get($parent->id) as $item)
                <li x-show="!search || !search || '{{ $item->name }}'.includes(search)" class="group space-y-1 hover:text-main-400 {{ $tag_id == $item->id ? 'text-main-400' : '' }}">
                    <a href="{{ route('materials.index', array_merge($tmp_query, ['tid' => $item->id])) }}">
                        <span>{{ $item->name }}</span>
                        @if ($item->materials_count > 0)
                        <span class="text-xs rounded-full px-2 py-1 bg-slate-100 font-semibold text-slate-700">{{ $item->materials_count }}</span>
                        @endif
                    </a>
                    @if($act == 'tag-edit')
                    <div class="flex space-x-2 text-sm">
                        <a x-on:click.prevent="edit({{ $item->toJson() }})"href="#" class="text-slate-700">
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
            </ul>
            @else
            <div class="mt-3 pl-5 space-y-3 text-slate-300">{{ __('empty') }}</div>
            @endif
        @endforeach
        <a href="{{ route('materials.index', array_merge($query, ['tid' => -1])) }}" class="block text-slate-900 hover:text-main-400 dark:text-slate-200 {{ request()->input('tid') == -1 ? 'text-main-400' : '' }}">
            <h2 class="text-base font-semibold flex items-center space-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                {{ __('Material Trash') }}
            </h2>
        </a>
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
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-main-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 stroke-main-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        </div>
                        <div class="mt-3 w-full text-center sm:mt-0 sm:ml-4 sm:text-left space-y-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ __('update tag') }}
                            </h3>
                            <div class="relative z-0 mb-6 w-full group">
                                <input x-model="parent_id" type="hidden" name="parent_id" />
                                <input x-model="name" autocomplete="off" type="text" name="name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-main-500 focus:outline-none focus:ring-0 focus:border-main-600 peer" placeholder=" " required />
                                <label for="name" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-main-600 peer-focus:dark:text-main-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('Tag Name') }}</label>
                            </div>
                            <div class="relative w-full group">
                                <label for="parent" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">{{ __('Parent Tag') }}</label>
                                <select x-model="parent_id" id="parent" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-main-500 focus:border-main-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-main-500 dark:focus:border-main-500">
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
            parent_id: null,
            update_url: null,
            drop_url: null,
            drop(item) {
                this.id = item.id;
                this.name = item.name;
                this.parent_id = item.parent_id;
                this.drop_url = '{{ route('material_tags.destroy', 'ref-id') }}'.replace('ref-id', item.id);
            },
            edit(item) {
                this.id = item.id;
                this.name = item.name;
                this.parent_id = item.parent_id;
                this.update_url = '{{ route('material_tags.update', 'ref-id') }}'.replace('ref-id', item.id);
            },
            reset() {
                this.update_url = null;
                this.drop_url = null;
            }
        }
    }
</script>
