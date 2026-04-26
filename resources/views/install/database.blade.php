<x-guest-layout>
    <div class="min-h-screen w-full bg-zinc-950 px-6 py-10 text-white">
        <div class="mx-auto flex max-w-4xl flex-col gap-8">
            <div class="rounded-[32px] border border-white/10 bg-[radial-gradient(circle_at_top_left,_rgba(249,115,22,0.16),_transparent_35%),linear-gradient(135deg,_rgba(24,24,27,1),_rgba(9,9,11,1))] px-8 py-10 shadow-2xl">
                <p class="text-sm uppercase tracking-[0.35em] text-orange-300/80">Шаг 2</p>
                <h1 class="mt-4 text-4xl font-semibold tracking-tight text-white">Подключение к базе данных</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-zinc-300">
                    На этом шаге CMS проверяет соединение с базой и сохраняет рабочие параметры в <code>.env</code>.
                    После этого можно переходить к миграциям и созданию администратора.
                </p>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <section class="rounded-[28px] border border-white/10 bg-zinc-900/80 p-6 shadow-xl">
                    @if (session('status') === 'database-configured')
                        <div class="mb-6 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                            Подключение успешно проверено, параметры сохранены. Следом можно запускать миграции.
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-rose-400/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-200">
                            Проверьте параметры подключения и попробуйте ещё раз.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('install.database.store') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="app_url" class="block text-sm font-medium text-zinc-100">Адрес сайта</label>
                            <input id="app_url" name="app_url" type="url" value="{{ $defaults['app_url'] }}" class="mt-2 block w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-orange-400/60" required />
                            <x-input-error class="mt-2" :messages="$errors->get('app_url')" />
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label for="connection" class="block text-sm font-medium text-zinc-100">Драйвер базы</label>
                                <select id="connection" name="connection" class="mt-2 block w-full rounded-xl border border-white/10 bg-zinc-900 px-4 py-3 text-sm text-white outline-none transition focus:border-orange-400/60">
                                    @foreach (['mysql' => 'MySQL', 'mariadb' => 'MariaDB', 'pgsql' => 'PostgreSQL', 'sqlsrv' => 'SQL Server'] as $value => $label)
                                        <option value="{{ $value }}" @selected($defaults['connection'] === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('connection')" />
                            </div>

                            <div>
                                <label for="host" class="block text-sm font-medium text-zinc-100">Хост</label>
                                <input id="host" name="host" type="text" value="{{ $defaults['host'] }}" class="mt-2 block w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-orange-400/60" required />
                                <x-input-error class="mt-2" :messages="$errors->get('host')" />
                            </div>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label for="port" class="block text-sm font-medium text-zinc-100">Порт</label>
                                <input id="port" name="port" type="number" value="{{ $defaults['port'] }}" class="mt-2 block w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-orange-400/60" required />
                                <x-input-error class="mt-2" :messages="$errors->get('port')" />
                            </div>

                            <div>
                                <label for="database" class="block text-sm font-medium text-zinc-100">Имя базы</label>
                                <input id="database" name="database" type="text" value="{{ $defaults['database'] }}" class="mt-2 block w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-orange-400/60" required />
                                <x-input-error class="mt-2" :messages="$errors->get('database')" />
                            </div>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label for="username" class="block text-sm font-medium text-zinc-100">Пользователь</label>
                                <input id="username" name="username" type="text" value="{{ $defaults['username'] }}" class="mt-2 block w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-orange-400/60" required />
                                <x-input-error class="mt-2" :messages="$errors->get('username')" />
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-zinc-100">Пароль</label>
                                <input id="password" name="password" value="{{ $defaults['password'] }}" class="mt-2 block w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-orange-400/60" />
                                <x-input-error class="mt-2" :messages="$errors->get('password')" />
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <a href="{{ route('install.index') }}" class="inline-flex rounded-xl border border-white/10 px-4 py-3 text-sm font-medium text-zinc-200 transition hover:bg-white/5">
                                Назад
                            </a>
                            <button type="submit" class="inline-flex rounded-xl bg-orange-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-orange-600">
                                Проверить и сохранить
                            </button>
                        </div>
                    </form>
                </section>

                <aside class="rounded-[28px] border border-white/10 bg-zinc-900/80 p-6 shadow-xl">
                    <h2 class="text-xl font-semibold text-white">Что делает этот шаг</h2>
                    <div class="mt-5 space-y-3 text-sm text-zinc-300">
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">
                            Проверяет соединение с выбранной СУБД через PDO.
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">
                            Сохраняет <code>APP_URL</code> и параметры <code>DB_*</code> в файл окружения.
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">
                            Подготавливает CMS к запуску миграций на следующем этапе.
                        </div>
                    </div>

                    @if (session('install.database'))
                        <div class="mt-6 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-4 text-sm text-emerald-100">
                            <p class="font-medium">Последняя сохранённая конфигурация</p>
                            <p class="mt-2 break-all text-emerald-200/90">{{ session('install.database.connection') }}://{{ session('install.database.username') }}@{{ session('install.database.host') }}:{{ session('install.database.port') }}/{{ session('install.database.database') }}</p>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</x-guest-layout>
