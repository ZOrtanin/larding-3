<x-guest-layout>
    <div class="min-h-screen w-full bg-zinc-950 px-6 py-10 text-white">
        <div class="mx-auto flex max-w-4xl flex-col gap-8">
            <div class="rounded-[32px] border border-white/10 bg-[radial-gradient(circle_at_top_left,_rgba(249,115,22,0.16),_transparent_35%),linear-gradient(135deg,_rgba(24,24,27,1),_rgba(9,9,11,1))] px-8 py-10 shadow-2xl">
                <p class="text-sm uppercase tracking-[0.35em] text-orange-300/80">Шаг 3</p>
                <h1 class="mt-4 text-4xl font-semibold tracking-tight text-white">Инициализация CMS</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-zinc-300">
                    На этом шаге мастер запускает миграции и базовые сидеры, чтобы подготовить таблицы, роли и стартовые блоки для дальнейшей работы.
                </p>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <section class="rounded-[28px] border border-white/10 bg-zinc-900/80 p-6 shadow-xl">
                    @if (session('status') === 'database-configured')
                        <div class="mb-6 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                            Параметры базы сохранены. Можно запускать инициализацию.
                        </div>
                    @endif

                    @if ($initialized)
                        <div class="mb-6 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                            База уже инициализирована в рамках текущей установки.
                        </div>
                    @endif

                    <div class="space-y-3 text-sm text-zinc-300">
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">Запуск `migrate --force`</div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">Сидирование ролей пользователей</div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">Загрузка стартовых блоков и шаблонов</div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('install.database') }}" class="inline-flex rounded-xl border border-white/10 px-4 py-3 text-sm font-medium text-zinc-200 transition hover:bg-white/5">
                            Назад
                        </a>

                        @if (! $initialized)
                            <form method="POST" action="{{ route('install.initialize.run') }}">
                                @csrf
                                <button type="submit" class="inline-flex rounded-xl bg-orange-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-orange-600">
                                    Запустить инициализацию
                                </button>
                            </form>
                        @else
                            <a href="{{ route('install.admin') }}" class="inline-flex rounded-xl bg-orange-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-orange-600">
                                Перейти к созданию администратора
                            </a>
                        @endif
                    </div>
                </section>

                <aside class="rounded-[28px] border border-white/10 bg-zinc-900/80 p-6 shadow-xl">
                    <h2 class="text-xl font-semibold text-white">Что важно знать</h2>
                    <div class="mt-5 space-y-3 text-sm text-zinc-300">
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">
                            Этот шаг можно запускать только после сохранения рабочих DB-настроек.
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">
                            Мы не создаём тестового супер-админа автоматически, чтобы учётка задавалась явно на следующем шаге.
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-guest-layout>
