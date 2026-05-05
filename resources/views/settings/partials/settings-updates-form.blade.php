<section
    x-data="cmsUpdateChecker({
        checkUrl: '{{ route('settings.check-cms-update') }}',
        currentVersion: @js($updateState['current_version']),
        installedVersion: @js($updateState['installed_version']),
        latestVersion: @js($updateState['latest_version']),
        archiveUrl: @js($updateState['archive_url']),
        updateAvailable: @js($updateState['update_available']),
        manifestError: @js($updateState['manifest_error']),
        checkedAt: @js($updateState['checked_at']),
        updateUrl: '{{ route('settings.update-cms') }}',
        progressUrl: '{{ route('settings.update-cms-progress') }}',
        updateStatus: @js(session('status')),
    })"
    x-init="init()"
>
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
            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100" x-text="state.currentVersion"></p>
        </div>

        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Установленная версия') }}</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100" x-text="state.installedVersion"></p>
        </div>

        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Последняя версия') }}</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-gray-100" x-text="state.latestVersion || 'Не проверялась'"></p>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap items-center justify-between gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900/40">

        <div class="max-w-2xl text-sm text-gray-600 dark:text-gray-400">
            <p x-show="!state.checkedAt && !checking" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Автоматическая проверка отключена. Нажмите кнопку ниже, чтобы узнать последнюю доступную версию.') }}
            </p>

            <p x-show="checking" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Проверяем обновления...') }}
            </p>

            <div x-show="updating" x-cloak class="rounded-lg border border-orange-200 bg-orange-50 p-4 dark:border-orange-900/50 dark:bg-orange-950/30">
                <p class="text-sm font-semibold text-orange-700 dark:text-orange-300">
                    {{ __('Обновление CMS запущено') }}
                </p>
                <p class="mt-2 text-sm text-orange-700/90 dark:text-orange-200/90" x-text="updateMessage"></p>
                <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-orange-200/70 dark:bg-orange-900/50">
                    <div class="h-full rounded-full bg-orange-500 transition-all duration-500 ease-out" :style="`width: ${updatePercent}%`"></div>
                </div>
                <p class="mt-2 text-xs font-medium text-orange-700 dark:text-orange-300">
                    <span x-text="updatePercent"></span>%
                </p>
                <p class="mt-3 text-xs text-orange-700/80 dark:text-orange-200/80">
                    {{ __('Не закрывайте вкладку и дождитесь завершения процесса.') }}
                </p>
            </div>

            <p x-show="state.checkedAt && state.manifestError" class="text-sm font-medium text-red-600 dark:text-red-400">
                {{ __('Не удалось проверить обновления:') }} <span x-text="state.manifestError"></span>
            </p>

            <p x-show="state.checkedAt && !state.manifestError && state.updateAvailable" class="text-sm font-medium text-emerald-700 dark:text-emerald-400">
                {{ __('Доступно обновление до версии') }} <span x-text="state.latestVersion"></span>.
            </p>

            <p x-show="state.checkedAt && !state.manifestError && !state.updateAvailable" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Новых обновлений пока нет. Установлена актуальная версия.') }}
            </p>

            <p x-show="state.checkedAt" class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                {{ __('Последняя проверка:') }} <span x-text="state.checkedAt"></span>
            </p>

            <p x-show="state.archiveUrl" class="mt-3 break-all text-xs text-gray-500 dark:text-gray-400">
                {{ __('Архив обновления:') }} <span x-text="state.archiveUrl"></span>
            </p>
        </div>

        <div class="max-w-2xl text-sm text-gray-600 dark:text-gray-400">
            <button
                type="button"
                @click="checkLatestVersion"
                :disabled="checking || updating"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
            >
                <span x-show="!checking">{{ __('Проверить последнюю версию') }}</span>
                <span x-show="checking">{{ __('Проверяем...') }}</span>
            </button>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
        <div class="max-w-2xl text-sm text-gray-600 dark:text-gray-400">
            {{ __('Во время обновления будут загружены новые файлы CMS, применены миграции и очищены кэши. Пользовательский контент и .env не перезаписываются.') }}
        </div>

       
    </div>

    <form method="post" action="{{ route('settings.update-cms') }}" class="mt-6" @submit.prevent="submitUpdate">
        @csrf

        <div class="flex flex-wrap items-center justify-between gap-4">
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

                @if (session('status') === 'cms-update-checked')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 4000)"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >{{ __('Проверка обновлений завершена.') }}</p>
                @endif

                <x-primary-button ::disabled="!state.checkedAt || !!state.manifestError || !state.updateAvailable || checking || updating">
                    <span x-show="!updating">{{ __('Обновить CMS') }}</span>
                    <span x-show="updating">{{ __('Обновляем...') }}</span>
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

<script>
    function cmsUpdateChecker(initialState) {
        return {
            checking: false,
            updating: false,
            updateMessage: 'Подготавливаем файлы обновления...',
            updatePercent: 0,
            progressPoller: null,
            state: {
                currentVersion: initialState.currentVersion,
                installedVersion: initialState.installedVersion,
                latestVersion: initialState.latestVersion,
                archiveUrl: initialState.archiveUrl,
                updateAvailable: initialState.updateAvailable,
                manifestError: initialState.manifestError,
                checkedAt: initialState.checkedAt,
            },
            startUpdate() {
                if (this.updating) {
                    return;
                }

                this.updating = true;
                this.updateMessage = 'Скачиваем архив и применяем обновление...';
                this.updatePercent = 5;

                window.setTimeout(() => {
                    if (this.updating) {
                        this.updateMessage = 'Применяем файлы, миграции и очищаем кэши...';
                    }
                }, 2500);
            },
            async submitUpdate() {
                if (this.updating || this.checking || !this.state.checkedAt || this.state.manifestError || !this.state.updateAvailable) {
                    return;
                }

                this.startUpdate();
                this.startProgressPolling();

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    const response = await fetch(initialState.updateUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    });

                    const payload = await response.json();

                    if (!response.ok) {
                        throw new Error(payload.message || 'Не удалось обновить CMS.');
                    }

                    this.state = {
                        ...this.state,
                        latestVersion: payload.state.latest_version,
                        archiveUrl: payload.state.archive_url,
                        updateAvailable: payload.state.update_available,
                        manifestError: payload.state.manifest_error,
                        checkedAt: payload.state.checked_at,
                        installedVersion: payload.state.latest_version || this.state.installedVersion,
                    };

                    this.updateMessage = payload.message || 'Обновление завершено.';
                    this.updatePercent = 100;
                } catch (error) {
                    this.state = {
                        ...this.state,
                        manifestError: error.message || 'Не удалось обновить CMS.',
                    };
                    this.updateMessage = error.message || 'Не удалось обновить CMS.';
                } finally {
                    window.setTimeout(() => {
                        this.stopProgressPolling();
                        this.updating = false;
                    }, 1200);
                }
            },
            startProgressPolling() {
                this.stopProgressPolling();

                this.progressPoller = window.setInterval(async () => {
                    try {
                        const response = await fetch(initialState.progressUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                        });

                        if (!response.ok) {
                            return;
                        }

                        const payload = await response.json();
                        const progress = payload.data || {};

                        if (progress.message) {
                            this.updateMessage = progress.message;
                        }

                        if (typeof progress.percent === 'number') {
                            this.updatePercent = progress.percent;
                        }

                        if (progress.status === 'completed' || progress.status === 'error') {
                            this.stopProgressPolling();
                        }
                    } catch (error) {
                        // ignore polling issues during update
                    }
                }, 900);
            },
            stopProgressPolling() {
                if (this.progressPoller) {
                    window.clearInterval(this.progressPoller);
                    this.progressPoller = null;
                }
            },
            async checkLatestVersion() {
                if (this.checking) {
                    return;
                }

                this.checking = true;

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    const response = await fetch(initialState.checkUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    });

                    const payload = await response.json();

                    if (!response.ok) {
                        throw new Error(payload.message || 'Не удалось проверить обновления.');
                    }

                    this.state = {
                        ...this.state,
                        latestVersion: payload.state.latest_version,
                        archiveUrl: payload.state.archive_url,
                        updateAvailable: payload.state.update_available,
                        manifestError: payload.state.manifest_error,
                        checkedAt: payload.state.checked_at,
                    };
                } catch (error) {
                    this.state = {
                        ...this.state,
                        latestVersion: null,
                        archiveUrl: null,
                        updateAvailable: false,
                        manifestError: error.message || 'Не удалось проверить обновления.',
                        checkedAt: new Date().toLocaleString(),
                    };
                } finally {
                    this.checking = false;
                }
            },
            init() {
                if (initialState.updateStatus === 'cms-updated') {
                    this.updating = false;
                }
            },
        };
    }
</script>
