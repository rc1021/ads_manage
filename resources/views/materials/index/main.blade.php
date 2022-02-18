<div x-data="materials_index_items()">
    @include('materials.index.' . $type_str)
    <!-- Edit Modal -->
    <div x-show="update_url" style="display: none" class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <form method="POST" :action="update_url">
            @csrf
            @method('PUT')
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block align-top sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-main-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 stroke-main-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        </div>
                        <div class="mt-3 w-full text-center sm:mt-0 sm:ml-4 sm:text-left space-y-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ __('Material Edit') }}
                            </h3>
                            <div class="relative z-0 mb-6 w-full group px-6">
                                <input x-model="title" autocomplete="off" type="text" name="title" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-main-500 focus:outline-none focus:ring-0 focus:border-main-600 peer" placeholder=" " required />
                                <label for="title" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-main-600 peer-focus:dark:text-main-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('Material Title') }}</label>
                            </div>
                            <div class="relative px-6 space-y-4">
                                <label class="relative block">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                                        <svg class="h-5 w-5 fill-slate-300" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <span class="sr-only">Tap tag</span>
                                    <input x-model.debounce.250ms="search" x-on:keydown.enter.prevent="add(search)" type="text" name="search" autocomplete="off" class="block bg-white w-full border border-slate-300 rounded-md py-2 pl-9 pr-3 shadow-sm placeholder:text-slate-400 focus:outline-none focus:border-main-500 focus:ring-main-500 focus:ring-1 sm:text-sm" placeholder="Tapping for add tag...">
                                    <div x-show="search" style="display: none;" class="block z-20 bg-white w-full border border-slate-300 rounded-md absolute mt-1 divide-y">
                                        <template x-for="name in autocompelte.filter(words => search && words.toLowerCase().includes(search.toLowerCase()))" :key="'autocompelte_' + name">
                                            <div x-on:click.prevent="add(name)" x-text="name" class="p-2 px-4 first:rounded-t-md text-gray-600 bg-white hover:bg-gray-100 cursor-pointer"></div>
                                        </template>
                                        <div class="p-2 px-4 first:rounded-t-md rounded-b-md text-gray-600 bg-gray-100 cursor-pointer">{{ __('or `Enter` to add') }} [<span x-text="search"></span>]</div>
                                    </div>
                                </label>
                                <template x-for="keyword in keywords" :key="'tag_' + keyword">
                                    <div class="flex items-center">
                                        <input :id="'checkbox-' + keyword" x-model="checkboxes[keyword]" name="tags[]" :value="keyword" :aria-describedby="'checkbox-' + keyword" type="checkbox" class="w-4 h-4 text-main-600 bg-gray-100 rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                                        <label :for="'checkbox-' + keyword" x-text="keyword" class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300"></label>
                                    </div>
                                </template>
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
    <!-- Drop Modal -->
    <div x-show="drop_url" style="display: none" class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                        {{ __('drop material to the trash') }}
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                        {{ __('Are you sure if you put it in the trash?') }}
                        </p>
                        <p class="text-sm text-gray-500">
                        {{ __('item title') }}: <span x-text="title" class="text-red-600"></span>
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
    function materials_index_items() {
        return {
            title: null,
            id: null,
            search: null,
            keywords: [],
            checkboxes: [],
            autocompelte: {!! $tag_names !!},
            update_url: null,
            drop_url: null,
            add(keyword) {
                this.keywords.push(keyword);
                this.keywords = _.union(this.keywords);
                this.checkboxes[keyword] = true;
                this.search = null;
            },
            drop(item) {
                this.id = item.id;
                this.title = item.title;
                this.drop_url = '{{ route('materials.destroy', 'ref-id') }}'.replace('ref-id', item.id);
            },
            edit(item) {
                this.id = item.id;
                this.title = item.title;
                this.keywords = _.map(item.tags, 'name');
                this.checkboxes = _.fromPairs(_.zip(this.keywords, _.map(new Array(this.keywords.length), () => true)));
                this.update_url = '{{ route('materials.update', 'ref-id') }}'.replace('ref-id', item.id);
            },
            reset() {
                this.search = null,
                this.keywords = [],
                this.checkboxes = [],
                this.update_url = null;
                this.drop_url = null;
            }
        }
    }
</script>
