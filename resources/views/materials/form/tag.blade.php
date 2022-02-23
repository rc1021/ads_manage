<div x-data="materials_form_tag()" id="materials-form-tag">
    {{-- button --}}
    <a x-on:click.prevent="modal = true" href="#" class="group block max-w-xs rounded-lg p-3 py-2 bg-white ring-1 ring-slate-900/5 shadow space-y-3 hover:bg-main-500 hover:ring-main-500">
        <div class="flex items-center space-x-1">
            <svg class="h-5 w-5 stroke-main-500 group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            <h3 class="hidden lg:inline-block text-sm text-slate-900 font-semibold group-hover:text-white">{{ __('New Tag') }}</h3>
        </div>
    </a>
    <!-- Modal -->
    <div x-show="modal" style="display: none" class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <form method="POST" action="{{ route('material_tags.store') }}">
            @csrf
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
                            {{ __('add new tag') }}
                        </h3>
                        <div class="relative z-0 mb-6 w-full group">
                            <input autocomplete="off" type="text" name="name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-main-600 peer" placeholder=" " required />
                            <label for="name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-main-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('Tag Name') }}</label>
                        </div>
                        <div class="relative w-full group">
                            <label for="parent" class="block mb-2 text-sm font-medium text-gray-900">{{ __('Folder Tag') }}</label>
                            <select name="folder_id" id="parent" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-main-500 focus:border-main-500 block w-full p-2.5">
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
                    {{ __('Create') }}
                </button>
                <button x-on:click.prevent="modal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Cancel') }}
                </button>
                </div>
            </div>
            </div>
        </form>
    </div>
</div>
<script>
    function materials_form_tag () {
        return {
            type: 1, // 新增類型
            modal: false,
            texts: [null],
            add_text() {
                this.texts.push(null);
            }
        }
    }
</script>
