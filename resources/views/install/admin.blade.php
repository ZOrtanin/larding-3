<x-guest-layout>
    <div class="min-h-screen w-full bg-zinc-950 px-6 py-10 text-white">
        <div class="mx-auto flex max-w-4xl flex-col gap-8">
            <div class="rounded-[32px] border border-white/10 bg-[radial-gradient(circle_at_top_left,_rgba(249,115,22,0.16),_transparent_35%),linear-gradient(135deg,_rgba(24,24,27,1),_rgba(9,9,11,1))] px-8 py-10 shadow-2xl">
                <p class="text-sm uppercase tracking-[0.35em] text-orange-300/80">Шаг 4</p>
                <h1 class="mt-4 text-4xl font-semibold tracking-tight text-white">Создание первого администратора</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-zinc-300">
                    Последний шаг установки. После создания супер-администратора CMS будет помечена как установленная и откроет доступ в панель управления.
                </p>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <section class="rounded-[28px] border border-white/10 bg-zinc-900/80 p-6 shadow-xl">
                    @if (session('status') === 'installation-initialized')
                        <div class="mb-6 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                            Инициализация базы завершена. Осталось создать первого пользователя.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('install.admin.store') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-zinc-100">Имя администратора</label>
                            <input id="name" name="name" type="text" value="{{ $defaults['name'] }}" class="mt-2 block w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-orange-400/60" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-zinc-100">Email</label>
                            <input id="email" name="email" type="email" value="{{ $defaults['email'] }}" class="mt-2 block w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-orange-400/60" required />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label for="password" class="block text-sm font-medium text-zinc-100">Пароль</label>
                                <input id="password" name="password" type="password" class="mt-2 block w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-orange-400/60" required />
                                <x-input-error class="mt-2" :messages="$errors->get('password')" />
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-zinc-100">Подтверждение пароля</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="mt-2 block w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-orange-400/60" required />
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <a href="{{ route('install.initialize') }}" class="inline-flex rounded-xl border border-white/10 px-4 py-3 text-sm font-medium text-zinc-200 transition hover:bg-white/5">
                                Назад
                            </a>
                            <button type="submit" class="inline-flex rounded-xl bg-orange-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-orange-600">
                                Завершить установку
                            </button>
                        </div>
                    </form>
                </section>

                <aside class="rounded-[28px] border border-white/10 bg-zinc-900/80 p-6 shadow-xl">
                    <h2 class="text-xl font-semibold text-white">Что произойдёт дальше</h2>
                    <div class="mt-5 space-y-3 text-sm text-zinc-300">
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">
                            Пользователь будет создан с ролью <code>super_admin</code>.
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">
                            CMS сохранит флаг установки в таблице настроек и перестанет редиректить на `/install`.
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3">
                            После этого откроется админ-панель.
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-guest-layout>
