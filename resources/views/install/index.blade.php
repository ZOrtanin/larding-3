<x-guest-layout>
    <div class="min-h-screen w-full bg-zinc-950 px-6 py-10 text-white">
        <div class="mx-auto flex max-w-5xl flex-col gap-8">
            <div class="overflow-hidden rounded-[32px] border border-white/10 bg-[radial-gradient(circle_at_top_left,_rgba(249,115,22,0.18),_transparent_35%),linear-gradient(135deg,_rgba(24,24,27,1),_rgba(9,9,11,1))] shadow-2xl">
                <div class="grid gap-8 px-8 py-10 md:grid-cols-[1.2fr_0.8fr] md:px-10">
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-orange-300/80">CMS Larding</p>
                        <h1 class="mt-4 text-4xl font-semibold tracking-tight text-white md:text-5xl">Мастер установки CMS</h1>
                        <p class="mt-4 max-w-2xl text-base leading-7 text-zinc-300">
                            Подготовили первый этап установки: проверку окружения и изоляцию приложения до завершения настройки.
                            Следующим шагом подключим форму базы данных, запись <code>.env</code>, миграции и создание администратора.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <div class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-zinc-200">
                                Этап 1: состояние установки
                            </div>
                            <div class="rounded-full border border-orange-400/30 bg-orange-500/10 px-4 py-2 text-sm text-orange-200">
                                Этап 2: подключение базы данных
                            </div>
                        </div>

                        <div class="mt-8">
                            <a href="{{ route('install.database') }}" class="inline-flex rounded-xl bg-orange-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-orange-600">
                                Продолжить установку
                            </a>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-black/20 p-6 backdrop-blur">
                        <p class="text-sm font-medium text-zinc-300">Статус системы</p>

                        @if ($installationCompleted)
                            <div class="mt-4 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                                CMS уже отмечена как установленная.
                            </div>
                        @elseif ($allChecksPassed)
                            <div class="mt-4 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                                Сервер готов к следующему шагу установки.
                            </div>
                        @else
                            <div class="mt-4 rounded-2xl border border-amber-400/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-200">
                                Есть незавершённые проверки. Их нужно исправить перед продолжением.
                            </div>
                        @endif

                        <div class="mt-6 space-y-3 text-sm text-zinc-300">
                            <div class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                <span>Проверка окружения</span>
                                <span class="{{ $allChecksPassed ? 'text-emerald-300' : 'text-amber-300' }}">
                                    {{ $allChecksPassed ? 'OK' : 'Требует внимания' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                <span>Настройка базы данных</span>
                                <span class="text-zinc-500">Следующий этап</span>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                <span>Создание администратора</span>
                                <span class="text-zinc-500">Следующий этап</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <section class="rounded-[28px] border border-white/10 bg-zinc-900/80 p-6 shadow-xl">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-semibold text-white">Требования сервера</h2>
                            <p class="mt-1 text-sm text-zinc-400">Базовые зависимости, без которых Laravel CMS не сможет установиться.</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        @foreach ($requirements as $requirement)
                            <div class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $requirement['label'] }}</p>
                                    <p class="text-xs text-zinc-500">{{ $requirement['value'] }}</p>
                                </div>
                                <span class="text-sm {{ $requirement['passed'] ? 'text-emerald-300' : 'text-rose-300' }}">
                                    {{ $requirement['passed'] ? 'OK' : 'Ошибка' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-[28px] border border-white/10 bg-zinc-900/80 p-6 shadow-xl">
                    <h2 class="text-xl font-semibold text-white">Права на каталоги</h2>
                    <p class="mt-1 text-sm text-zinc-400">Эти директории должны быть доступны на запись, чтобы установка и работа CMS проходили корректно.</p>

                    <div class="mt-6 space-y-3">
                        @foreach ($permissions as $permission)
                            <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">
                                <div class="flex items-center justify-between gap-4">
                                    <p class="text-sm font-medium text-white">{{ $permission['label'] }}</p>
                                    <span class="text-sm {{ $permission['passed'] ? 'text-emerald-300' : 'text-rose-300' }}">
                                        {{ $permission['passed'] ? 'OK' : 'Нет доступа' }}
                                    </span>
                                </div>
                                <p class="mt-1 break-all text-xs text-zinc-500">{{ $permission['path'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-guest-layout>
