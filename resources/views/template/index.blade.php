<x-app-layout :site-name="$site_name" :site-description="$site_description" :head-html="$head_html" :front-css-url="$front_css_url" :body-start-html="$body_start_html" :body-end-html="$body_end_html" :front-js="$front_js">
    @php($hasAssignedRole = (bool) Auth::user()?->role_id)
    @auth
        @if ($hasAssignedRole)
            <section class="fixed w-full z-10 border-b border-gray-200 bg-white/70 backdrop-blur dark:border-gray-700 dark:bg-gray-800/70 " style="margin-top: 0px;">
                        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                            <div>
                                <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Панель инструментов</h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Управление блоками и редактированием главной страницы.</p>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-2">
                                    <label for="block-picker-select" class="text-sm font-medium text-gray-700 dark:text-gray-200">Выбрать блок</label>
                                    <select id="block-picker-select" class="min-w-[280px] rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 outline-none focus:border-orange-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                        <option value="">Выберите блок</option>
                                        @foreach (($editorBlocks ?? collect()) as $blockOption)
                                            <option value="{{ $blockOption['id'] }}">
                                                [{{ $blockOption['placement'] }}] #{{ $blockOption['order'] ?: $blockOption['id'] }} - {{ $blockOption['name'] }}{{ $blockOption['is_system'] ? ' (системный)' : '' }}{{ $blockOption['is_visible'] ? '' : ' (скрыт)' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button id="block-picker-open" type="button" class="inline-flex rounded-xl bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500 dark:bg-gray-700 dark:hover:bg-gray-600">
                                        Открыть
                                    </button>
                                </div>
                                <button type="button" class="js-add-block-button inline-flex rounded-xl bg-orange-500 px-3 py-2 text-sm font-medium text-gray-50 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
                                    + Добавить блок
                                </button>
                            </div>
                        </div>
                    </section>

           <div class="h-[80px] w-full"></div>    
       @endif     
    @endauth
    
    {!! $block !!}
    
</x-app-layout>
