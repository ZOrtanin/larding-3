<x-app-layout :site-name="$site_name" :site-description="$site_description">
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
