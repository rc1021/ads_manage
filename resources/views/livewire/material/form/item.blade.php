<div x-data="{
    'modal': $wire.entangle('modal'),
    'type': $wire.entangle('type'),
}">
    {{-- toastr --}}
    @include('livewire.toastr')
    {{-- button --}}
    <a x-on:click.prevent="modal = true" href="#" class="group block max-w-xs rounded-lg p-3 py-2 bg-white ring-1 ring-slate-900/5 shadow space-y-3 hover:bg-sky-500 hover:ring-sky-500">
        <div class="flex items-center space-x-1">
            <svg class="h-5 w-5 stroke-sky-500 group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
            </svg>
            <h3 class="hidden lg:inline-block text-sm text-slate-900 font-semibold group-hover:text-white">{{ __('New Material') }}</h3>
        </div>
    </a>
    <!-- Modal -->
    <div x-show="modal" style="display: none" wire:ignore.self class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <form wire:submit.prevent="update">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block align-top sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full sm:max-w-screen-sm md:max-w-screen-md lg:max-w-screen-lg xl:max-w-screen-xl">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-sky-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 stroke-sky-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                                </svg>
                            </div>
                            <div class="mt-3 w-full text-center sm:mt-0 sm:ml-4 sm:text-left space-y-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    {{ __('add new material') }}
                                </h3>
                                <div class="flex space-x-4">
                                    @include('livewire.material.form.item.type')
                                </div>
                                <div x-show="type == 1">
                                    @include('livewire.material.form.item.text')
                                </div>
                                <div x-show="type == 2">
                                    @include('livewire.material.form.item.image')
                                </div>
                                <div x-show="type == 8">
                                    @include('livewire.material.form.item.video')
                                </div>
                                {{-- tag --}}
                                <div wire:ignore class="flex items-center space-x-4 pt-5">
                                    <div class="flex-none">{{ __('Tag') }}</div>
                                    <input id="tag_for_new_material" class="flex-1" type="text" wire:model.lazy="tags" />
                                    <div class="flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                        <span class="text-sm text-gray-500">為這些素材新增檔案夾，方便分類管理 {{ $tags }}</span>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            var tagify = new Tagify (document.getElementById('tag_for_new_material'), {whitelist:[{"value":"foo", "editable":false}, {"value":"bar"}, {value:"banana", color:"yellow"}, {value:"apple", color:"red"}, {value:"watermelon", color:"green"}]});
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-sky-600 text-base font-medium text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 sm:ml-3 sm:w-auto sm:text-sm">
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
