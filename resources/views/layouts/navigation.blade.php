<nav x-data="{ open: false }" class="fixed w-full z-20 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    @php($hasAssignedRole = (bool) Auth::user()?->role_id)
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <!-- <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-larding-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div> -->

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('template.index')" :active="request()->routeIs('template.index')">
                        {{ __('Cайт') }}
                    </x-nav-link>

                    @if ($hasAssignedRole)
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Панель') }}
                        </x-nav-link>

                        <x-nav-link :href="route('lids')" :active="request()->routeIs('lids')">
                            {{ __('Лиды') }}
                        </x-nav-link>

                        <x-nav-link :href="route('statistics')" :active="request()->routeIs('statistics')">
                            {{ __('Статистика') }}
                        </x-nav-link>

                        <x-nav-link :href="route('files')" :active="request()->routeIs('files')">
                            {{ __('Фаилы') }}
                        </x-nav-link>

                        <x-nav-link :href="route('settings.edit')" :active="request()->routeIs('settings.edit')">
                            {{ __('Настройки') }}
                        </x-nav-link>
                    @endif

                </div>

            </div>
            
            @if ($hasAssignedRole)
            <el-dialog>
              <dialog id="drawer" aria-labelledby="drawer-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-hidden bg-transparent not-open:hidden backdrop:bg-transparent">
                <el-dialog-backdrop class="absolute inset-0 bg-gray-900/50 transition-opacity duration-500 ease-in-out data-closed:opacity-0"></el-dialog-backdrop>

                <div tabindex="0" class="absolute inset-0 pl-10 focus:outline-none sm:pl-16">
                  <el-dialog-panel id="block-editor-panel" class="group/dialog-panel relative ml-auto block h-full w-full max-w-md transform transition duration-500 ease-in-out data-closed:translate-x-full sm:duration-700">
                    <div id="block-editor-resize-handle" class="absolute inset-y-0 left-0 z-20  w-5 -translate-x-1/2 cursor-col-resize" title="Потяните, чтобы изменить ширину редактора">
                      <div class="absolute inset-y-8 left-1/2 h-[100px] w-1 -translate-x-1/2 rounded-full bg-white/20 transition group-hover/dialog-panel:bg-orange-400/80 my-auto"></div>
                    </div>
                    <!-- Close button, show/hide based on slide-over state. -->
                    <div class="absolute top-0 left-0 -ml-8 flex pt-4 pr-2 duration-500 ease-in-out group-data-closed/dialog-panel:opacity-0 sm:-ml-10 sm:pr-4">
                      <button id="drawer-close-button" type="button" class="relative rounded-md text-gray-400 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                        <span class="absolute -inset-2.5"></span>
                        <span class="sr-only">Close panel</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                          <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                      </button>
                    </div>

                    <div class="relative flex h-full flex-col overflow-y-auto bg-gray-800 py-6 shadow-xl after:absolute after:inset-y-0 after:left-0 after:w-px after:bg-white/10">
                      <div class="px-4 sm:px-6">
                        <h2 id="drawer-title" class="text-base font-semibold text-white">Добавить блок</h2>
                      </div>

                      <div class="relative mt-6 flex-1 px-4 sm:px-6">
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>
                        <form id="block-form" action="{{ route('settings.create') }}" method="POST" >
                            @csrf
                            <input id="block-form-method" type="hidden" name="_method" value="PATCH">

                            <!-- Имя блока -->
                            <div id="block-editor-main-fields" class="mb-3 grid grid-cols-1 gap-3">
                              <div class="min-w-0">
                                <label for="block-name" class="block text-sm/6 font-medium text-white">Имя блока</label>
                                <div class="mt-2">
                                  <input id="block-name" type="text" name="name" autocomplete="given-name" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                                </div>
                              </div>

                              <div class="min-w-0">
                                <label for="block-description" class="block text-sm/6 font-medium text-white">Описание блока</label>
                                <div class="mt-2">
                                  <input id="block-description" type="text" name="description" autocomplete="given-description" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                                </div>
                              </div>
                            </div>

                            
                            <div class="col-span-full mb-3">
                              <label for="block-content" class="block text-sm/6 font-medium text-white">Редактор</label>
                              <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 p-1">
                                  <button type="button" data-editor-tab="html" class="block-editor-tab inline-flex rounded-lg bg-orange-500 px-3 py-1.5 text-sm font-medium text-white transition">HTML</button>
                                  <button type="button" data-editor-tab="css" class="block-editor-tab inline-flex rounded-lg px-3 py-1.5 text-sm font-medium text-gray-300 transition hover:text-white">CSS</button>
                                </div>
                                <label class="inline-flex items-center gap-2 text-xs text-gray-300">
                                  <input id="block-content-line-wrapping" type="checkbox" class="rounded border-white/10 bg-white/5 text-orange-500 focus:ring-orange-500" checked />
                                  Перенос строк
                                </label>
                              </div>
                              <div id="block-editor-pane-html" class="block-editor-pane mt-3">
                                <label for="block-content" class="block text-sm/6 font-medium text-white">HTML контент</label>
                                <div class="mt-2">
                                  <textarea id="block-content" name="content" rows="13" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"></textarea>
                                </div>
                                <p class="mt-3 text-sm/6 text-gray-400">HTML шаблон блока.</p>
                              </div>
                              <div id="block-editor-pane-css" class="block-editor-pane mt-3 hidden">
                                <label for="block-custom-css" class="block text-sm/6 font-medium text-white">CSS блока</label>
                                <div class="mt-2">
                                  <textarea id="block-custom-css" name="custom_css" rows="13" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"></textarea>
                                </div>
                                <p class="mt-3 text-sm/6 text-gray-400">Эти стили будут собраны в общий файл <code class="text-gray-200">front-custom.css</code>.</p>
                              </div>
                            </div>

                            <div id="block-editor-meta-fields" class="mb-3 grid grid-cols-1 gap-3">
                              <div class="min-w-0">
                                <label for="block-placement" class="block text-sm/6 font-medium text-white">Место вывода</label>
                                <div class="mt-2">
                                  <select id="block-placement" name="placement" class="block w-full rounded-md bg-gray-800 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
                                    <option value="content">content</option>
                                    <option value="head">head</option>
                                    <option value="body_start">body_start</option>
                                    <option value="body_end">body_end</option>
                                    <option value="front_css">front_css</option>
                                    <option value="front_js">front_js</option>
                                  </select>
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Системные layout-блоки редактируются здесь же, но удалить их нельзя.</p>
                              </div>

                              <div class="min-w-0">
                                <label for="block-template-select" class="block text-sm/6 font-medium text-white">Шаблон блока</label>
                                <div class="mt-2 flex items-center gap-2">
                                  <select id="block-template-select" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
                                    <option value="">Выберите шаблон</option>
                                  </select>
                                  <button id="block-template-apply" type="button" class="inline-flex rounded-md bg-gray-700 px-3 py-1.5 text-sm font-medium text-white hover:bg-gray-600">Применить</button>
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Подставляет название, описание и контент в форму.</p>
                              </div>
                            </div>

                            <div class="col-span-full mt-6">
                              <div class="flex items-center justify-between">
                                <label class="block text-sm/6 font-medium text-white">Переменные блока</label>
                                <button id="block-variable-add" type="button" class="inline-flex rounded-md bg-gray-700 px-3 py-1.5 text-sm font-medium text-white hover:bg-gray-600">+ Добавить переменную</button>
                              </div>
                              <div id="block-variables-list" class="mt-3 space-y-3"></div>
                              <p class="mt-3 text-sm/6 text-gray-400">Эти переменные можно использовать для дополнительных настроек блока.</p>
                            </div>



                        <div class="mt-4 flex items-center gap-3">
                          <button id="block-submit-button" type="submit" class="inline-flex rounded-xl bg-orange-500 text-gray-50 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500 h-10 p-1 px-3 my-auto font-medium text-sm items-center">+ Добавить блок</button>
                          <button id="block-delete-button" type="button" class="hidden inline-flex rounded-xl bg-red-600 text-gray-50 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500 h-10 p-1 px-3 my-auto font-medium text-sm items-center">Удалить блок</button>
                          <p id="block-form-status" class="mt-3 text-sm text-red-400"></p>
                        </div>
                        
                        </form>

                        <template id="block-variable-template">
                          <div class="block-variable-item rounded-xl border border-white/10 bg-white/5 p-4">
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                              <div>
                                <label class="block text-sm/6 font-medium text-white">Ключ</label>
                                <input data-field="name" type="text" class="mt-2 block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                              </div>
                              <div>
                                <label class="block text-sm/6 font-medium text-white">Название</label>
                                <input data-field="label" type="text" class="mt-2 block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                              </div>
                              <div>
                                <label class="block text-sm/6 font-medium text-white">Тип</label>
                                <select data-field="type" class="mt-2 block w-full rounded-md bg-gray-800 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
                                  <option value="text">text</option>
                                  <option value="textarea">textarea</option>
                                  <option value="color">color</option>
                                  <option value="image">image</option>
                                  <option value="boolean">boolean</option>
                                </select>
                              </div>
                              <div>
                                <label class="block text-sm/6 font-medium text-white">Значение по умолчанию</label>
                                <input data-field="default_value" type="text" class="mt-2 block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                              </div>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                              <label class="inline-flex items-center gap-2 text-sm text-white">
                                <input data-field="required" type="checkbox" class="rounded border-white/10 bg-white/5 text-orange-500 focus:ring-orange-500" />
                                Обязательная
                              </label>
                              <button type="button" data-action="remove-variable" class="inline-flex rounded-md bg-red-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-500">Удалить</button>
                            </div>
                          </div>
                        </template>

                      </div>
                    </div>
                  </el-dialog-panel>
                </div>
              </dialog>
            </el-dialog>
            @endif

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

            <!-- Центр уведомлений -->
            <x-dropdown align="right" width="400">
                    <x-slot name="trigger">  
                        <button type="button" class="relative rounded-full p-1 text-gray-400 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
                              <span class="absolute -inset-1.5"></span>
                              <span class="sr-only">View notifications</span>
                              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                                <path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                              @if ($unreadNotificationsCount > 0)
                                  <div class="absolute top-[13px] left-[-7px] z-10 h-6 rounded-full bg-white p-[8px] pt-[4px] leading-none text-gray-100 dark:bg-orange-500">
                                      {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                                  </div>
                              @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @forelse ($notifications as $notification)
                            <div class="group relative mx-1 rounded-lg p-1 text-sm/6 hover:bg-white/5">
                                <div class="flex items-center gap-x-6 rounded-lg p-4 {{ $notification->is_read ? 'opacity-70' : '' }}">
                                    <div class="flex size-11 flex-none items-center justify-center rounded-lg bg-gray-700/50 group-hover:bg-gray-700">
                                        @if ($notification->type === 'lead')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-gray-400 group-hover:text-white fill-gray-400 group-hover:fill-white" viewBox="0 0 512 512"><path d="M408 64H104a56.16 56.16 0 00-56 56v192a56.16 56.16 0 0056 56h40v80l93.72-78.14a8 8 0 015.13-1.86H408a56.16 56.16 0 0056-56V120a56.16 56.16 0 00-56-56z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><circle cx="160" cy="216" r="32"/><circle cx="256" cy="216" r="32"/><circle cx="352" cy="216" r="32"/></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-gray-400 group-hover:text-white" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M256 48C141.13 48 48 141.13 48 256s93.13 208 208 208 208-93.13 208-208S370.87 48 256 48zm16 304h-32v-32h32zm0-64h-32V160h32z"/></svg>
                                        @endif
                                    </div>
                                    <div class="flex-auto">
                                        <a href="{{ route('notifications.open', $notification) }}" class="block font-semibold text-white">
                                            {{ $notification->title }}
                                            <span class="absolute inset-0"></span>
                                        </a>
                                        <p class="mt-1 text-gray-400">{{ $notification->message ?: 'Без описания' }}</p>
                                        <p class="mt-2 text-xs text-gray-500">{{ $notification->created_at?->format('d.m.Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-6 text-center text-sm text-gray-400">
                                Уведомлений пока нет
                            </div>
                        @endforelse

                        @if ($notifications->isNotEmpty())
                            <div class="mt-1 bg-gray-700/50">
                                <form method="POST" action="{{ route('notifications.read_all') }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="flex w-full items-center justify-center gap-x-2.5 p-3 text-sm/6 font-semibold text-white hover:bg-gray-700/50">
                                        Отметить как прочитанное
                                    </button>
                                </form>
                            </div>
                        @endif

                    </x-slot>
                </x-dropdown>

                <!-- Профиль -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">

                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Профиль') }}
                        </x-dropdown-link>

                        @if (Auth::user()?->role?->slug === 'super_admin')
                            <x-dropdown-link :href="route('users.index')">
                                {{ __('Пользователи') }}
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Выход') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('template.index')" :active="request()->routeIs('template.index')">
                {{ __('Cайт') }}
            </x-responsive-nav-link>

            @if ($hasAssignedRole)
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Панель') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('lids')" :active="request()->routeIs('lids')">
                    {{ __('Лиды') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('statistics')" :active="request()->routeIs('statistics')">
                    {{ __('Статистика') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('files')" :active="request()->routeIs('files')">
                    {{ __('Фаилы') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('settings.edit')" :active="request()->routeIs('settings.edit')">
                    {{ __('Настройки') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
