<div x-data="materials_form_item()" id="materials-form-item">
    {{-- button --}}
    <a x-on:click.prevent="modal = true" href="#" class="group block max-w-xs rounded-lg p-3 py-2 bg-white ring-1 ring-slate-900/5 shadow space-y-3 hover:bg-main-500">
        <div class="flex items-center space-x-1">
            <svg class="h-5 w-5 stroke-main-500 group-hover:stroke-white" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
            </svg>
            <h3 class="hidden lg:inline-block text-sm text-slate-900 font-semibold group-hover:text-white">{{ __('+文案/素材') }}</h3>
        </div>
    </a>
    <!-- Modal -->
    <div x-show="modal" style="display: none" class="fixed z-30 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <form @submit="validate" method="POST" action="{{ route('material.items.store') }}">
            @csrf
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block align-top sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full sm:max-w-screen-sm md:max-w-screen-md lg:max-w-screen-lg xl:max-w-screen-xl">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-main-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 stroke-main-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                                </svg>
                            </div>
                            <div class="mt-3 w-full text-center sm:mt-0 sm:ml-4 sm:text-left space-y-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    {{ __('+文案/素材') }}
                                </h3>
                                {{-- items --}}
                                <div class="bg-white flex">
                                    <nav class="flex mr-auto">
                                        <label for="tab_text" x-bind:class="{ 'border-b-2 font-medium border-main-500 text-main-500': tab == 'text', 'text-gray-600': tab != 'text' }" class="group py-4 px-6 block hover:text-main-500 focus:outline-none">
                                            <svg x-bind:class="{ 'stroke-main-500': tab == 'text', 'stroke-gray-600': tab != 'text' }" class="h-5 w-5 inline-block mr-1 group-hover:stroke-main-500" fill="none" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                            </svg>
                                            {{ __('文案') }}
                                            <input x-model="tab" type="radio" id="tab_text" value="text" class="invisible" />
                                        </label>
                                        <label for="tab_fileupload" x-bind:class="{ 'border-b-2 font-medium border-main-500 text-main-500': tab == 'fileupload', 'text-gray-600': tab != 'fileupload' }" class="group py-4 px-6 block hover:text-main-500 focus:outline-none">
                                            <svg x-bind:class="{ 'stroke-main-500': tab == 'fileupload', 'stroke-gray-600': tab != 'fileupload' }" class="h-5 w-5 inline-block mr-1 group-hover:stroke-main-500" fill="none" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                            {{ __('上傳檔案') }}
                                            <input x-model="tab" type="radio" id="tab_fileupload" value="fileupload" class="invisible" />
                                        </label>
                                        <label for="tab_url" x-bind:class="{ 'border-b-2 font-medium border-main-500 text-main-500': tab == 'url', 'text-gray-600': tab != 'url' }" class="group py-4 px-6 block hover:text-main-500 focus:outline-none">
                                            <svg x-bind:class="{ 'stroke-main-500': tab == 'url', 'stroke-gray-600': tab != 'url' }" class="h-5 w-5 inline-block mr-1 group-hover:stroke-main-500" fill="none" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                            {{ __('Youtube 網址') }}
                                            <input x-model="tab" type="radio" id="tab_url" value="url" class="invisible" />
                                        </label>
                                    </nav>
                                </div>
                                <div class="pt-3">
                                    <div x-show="tab == 'text'" class="flex space-x-4">
                                        <div class="flex-none mt-2">{{ __('Copy Text') }}</div>
                                        <span x-text="foo"></span>
                                        <div class="flex-1 space-y-4 h-48 max-h-48 overflow-y-auto">
                                            <div class="grid grid-cols-1 gap-4">
                                                <template x-for="(text, index) in texts" :key="'add_text_' + index">
                                                    <div>
                                                        <div class="flex border-b">
                                                            <div class="flex flex-col flex-1">
                                                                <input x-model="texts[index]" @keyup="validate()" x-on:paste="paste_rows($event, index)" type="text" name="texts[]" class="w-full border-0 focus:outline-none p-2 sm:text-sm border-gray-300 rounded-md" :placeholder="'{{ __('Copy ') }}' + (index + 1) + ' (`Ctrl + v` to paste rows)'" autocomplete="off">
                                                            </div>
                                                            <template x-if="is_over(texts[index] || '')">
                                                                <div class="flex-none flex items-center justify-end bg-white text-sm mx-2">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block stroke-red-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                </div>
                                                            </template>
                                                            <div x-on:click="removeInput(text)" class="flex-none flex items-center justify-end bg-white text-sm cursor-pointer mx-2">
                                                                <svg class="h-4 w-4 stroke-red-500 hover:stroke-red-700" fill="none" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </div>
                                                            <div class="flex-none flex items-center justify-end bg-white text-sm mx-2">
                                                                <span x-text="to8bit_length(text || '') + '/25'"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <div x-ref="keyfornew" :key="'keyfornew'">
                                                    <div x-on:click="add_text" class="flex border border-dashed cursor-pointer rounded-md">
                                                        <div class="flex-1 cursor-pointer focus:outline-none p-2 bg-gray-100 text-gray-400 sm:text-sm">
                                                            {{ __('Add New One') }}
                                                        </div>
                                                        <div class="flex-none flex items-center justify-end text-sm bg-white cursor-pointer mx-2">
                                                            <svg class="h-4 w-4 stroke-main-500 hover:stroke-main-700" fill="none" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div x-show="tab == 'fileupload'" class="">
                                        <div id="drop_zone_images"
                                            class="flex flex-col relative divide-y rounded-lg cursor-pointer p-0.5 h-48 max-h-72 overflow-hidden overflow-y-auto border-4 border-dashed border-slate-200  hover:border-slate-400 after:content-[attr(title)] after:text-slate-400 after:flex after:items-center after:align-middle after:justify-center after:w-full after:h-full after:absolute after:font-semibold beofre:text-4xl"
                                            title="{{ __('Drag one or more video files to this Drop Zone ... (or click)') }}"
                                            accept="video/mp4,video/quicktime,image/gif,image/jpeg,image/jpg,image/png"
                                            data-rel="drop-uploader"
                                            data-type="{{ \TargetConvert\Material\Enums\MaterialType::Video }}"
                                            data-temporary-url="{{ route('material.snowflake.store') }}"
                                            data-url="{{ route('material.items.upload') }}"
                                            data-size-limit="{{ 30 * 1024 * 1024 }}">
                                            {{-- drop items --}}
                                            <span class="hidden text-red-600 bg-red-200"></span>
                                            <span class="hidden text-amber-600 bg-amber-200"></span>
                                        </div>
                                        <div class="text-sm text-gray-500 space-x-2">
                                            <span>{{ __('允許上傳檔案格式：') }}MP4, MOV, GIF, JPEG, JPG, PNG</span>
                                            <span>{{ __('圖檔尺寸比例限制：') }}[1:1]、[1.91:1]</span>
                                            <span>{{ __('影片尺寸比例限制：') }}[1:1]</span>
                                        </div>
                                    </div>
                                    <div x-show="tab == 'url'" class="">
                                        <textarea @keyup="validate()" name="urls" class="w-full h-48 max-h-72 border-slate-200  hover:border-slate-400 p-2 outline-0 border-4 rounded-lg" placeholder="{{ __('請輸入影片網址，每行一個有效網址') }}"></textarea>
                                    </div>
                                </div>
                                {{-- tag --}}
                                <div class="flex items-center space-x-4 pt-5">
                                    <div class="flex-none">{{ __('Tag') }}</div>
                                    <input id="tag_for_new_material" class="flex-1  rounded-md" type="text" name="tags" />
                                    <div class="flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 stroke-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                        <span class="text-sm text-gray-500">{{ __('為這些素材新增檔案夾，方便分類管理') }}</span>
                                    </div>
                                </div>
                                <script>
                                    window.addEventListener("load", function () {
                                        let el = document.getElementById('tag_for_new_material');
                                        let tagify = new Tagify (el, {
                                            whitelist: {!! $tag_names !!},
                                            dropdown: {
                                                enabled: 0,
                                            },
                                        });
                                    })
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button :disabled="!is_validate" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-main-600 text-base font-medium text-white hover:bg-main-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-75 disabled:cursor-not-allowed">
                            {{ __('Create') }}
                        </button>
                        <button x-on:click.prevent="modal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                    <div class="hidden bg-amber-200 text-amber-600 stroke-amber-600"></div>
                    <div class="hidden bg-main-200 text-main-600 stroke-main-600"></div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    function materials_form_item () {
        return {
            is_validate: true,
            tab: 'text',
            foo: '',
            type: 1, // 新增類型
            modal: false,
            texts: {!! json_encode(old('texts', [null]), JSON_UNESCAPED_UNICODE) !!},
            add_text(event) {
                let app = this;
                this.texts.push(null);
                setTimeout(function() {
                    app.$refs.keyfornew.scrollIntoView();
                }, 150);
            },
            removeInput(keyword) {
                _.remove(this.texts, function(n) {
                    return n == keyword;
                });
                if(this.texts.length == 0)
                    this.texts.push(null);
            },
            paste_rows(event) {
                let app = this;
                let clipboard = event.clipboardData.getData('Text').split(/\r?\n/).filter(row => row);
                if(clipboard.length > 1) {
                    this.texts = _.uniq(this.texts.concat(clipboard));
                    setTimeout(function () {
                        app.removeInput(clipboard.join(' '));
                        app.$refs.keyfornew.scrollIntoView();
                        app.validate();
                    }, 250 * Math.ceil(this.texts.length / 1000));
                }
            },
            to8bit(text) {
                return (text || '').replace(/[^ -~]/g, 'AA');
            },
            to8bit_length(text) {
                return this.to8bit(text).length;
            },
            is_over(text, max_length = 25) {
                return this.to8bit_length(text) > max_length;
            },
            validate() {
                // 驗證文案是否超過 25 字元
                this.is_validate = (this.texts || []).filter(f => this.is_over(f)).length == 0;
                return this.is_validate;
            }
        }
    }
</script>
