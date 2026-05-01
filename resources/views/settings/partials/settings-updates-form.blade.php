<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Обновление CMS') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Проверяйте доступность новой версии и запускайте обновление без переустановки.') }}
        </p>
    </header>

    <div class="mt-6 grid gap-4 md:grid-cols-3">
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Версия кода') }}</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $updateState['current_version'] }}</p>
        </div>

        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Установленная версия') }}</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $updateState['installed_version'] }}</p>
        </div>

        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Последняя версия') }}</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                {{ $updateState['latest_version'] ?? __('Недоступно') }}
            </p>
        </div>
    </div>

    <div class="mt-6 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900/40">
        @if ($updateState['manifest_error'])
            <p class="text-sm font-medium text-red-600 dark:text-red-400">
                {{ __('Не удалось проверить обновления:') }} {{ $updateState['manifest_error'] }}
            </p>
        @elseif ($updateState['update_available'])
            <p class="text-sm font-medium text-emerald-700 dark:text-emerald-400">
                {{ __('Доступно обновление до версии') }} {{ $updateState['latest_version'] }}.
            </p>
        @else
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Новых обновлений пока нет. Установлена актуальная версия.') }}
            </p>
        @endif

        @if ($updateState['archive_url'])
            <p class="mt-3 break-all text-xs text-gray-500 dark:text-gray-400">
                {{ __('Архив обновления:') }} {{ $updateState['archive_url'] }}
            </p>
        @endif
    </div>

    <form method="post" action="{{ route('settings.update-cms') }}" class="mt-6">
        @csrf

        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="max-w-2xl text-sm text-gray-600 dark:text-gray-400">
                {{ __('Во время обновления будут загружены новые файлы CMS, применены миграции и очищены кэши. Пользовательский контент и .env не перезаписываются.') }}
            </div>

            <div class="flex items-center gap-4">
                @if (session('status') === 'cms-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 4000)"
                        class="text-sm text-emerald-700 dark:text-emerald-400"
                    >{{ __('CMS успешно обновлена.') }}</p>
                @endif

                @if (session('status') === 'cms-update-not-available')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 4000)"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >{{ __('Новых обновлений не найдено.') }}</p>
                @endif

                <x-primary-button :disabled="(bool) $updateState['manifest_error'] || ! $updateState['update_available']">
                    {{ __('Обновить CMS') }}
                </x-primary-button>
            </div>
        </div>
    </form>

    @if (session('cms_update_error'))
        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-300">
            {{ session('cms_update_error') }}
        </div>
    @endif
</section>
