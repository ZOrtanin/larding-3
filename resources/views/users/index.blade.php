<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Пользователи
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold">Управление пользователями</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                Список зарегистрированных пользователей сайта.
                            </p>
                        </div>
                    </div>

                    @if (session('status') === 'user-role-updated')
                        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-500/30 dark:bg-green-500/10 dark:text-green-300">
                            Роль пользователя обновлена.
                        </div>
                    @endif

                    @if (session('status') === 'user-password-updated')
                        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-500/30 dark:bg-green-500/10 dark:text-green-300">
                            Пароль пользователя обновлён.
                        </div>
                    @endif

                    @if (session('status') === 'user-deleted')
                        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-500/30 dark:bg-green-500/10 dark:text-green-300">
                            Пользователь удалён.
                        </div>
                    @endif

                    @if (session('status') === 'user-role-protected' || session('status') === 'user-delete-protected' || session('status') === 'user-password-protected')
                        <div class="mt-4 rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-700 dark:border-yellow-500/30 dark:bg-yellow-500/10 dark:text-yellow-300">
                            Пользователь с ролью супер-администратора защищён от этого действия.
                        </div>
                    @endif

                    @if (session('status') === 'user-delete-self-blocked')
                        <div class="mt-4 rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-700 dark:border-yellow-500/30 dark:bg-yellow-500/10 dark:text-yellow-300">
                            Нельзя удалить текущего авторизованного пользователя.
                        </div>
                    @endif

                    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900/40">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Имя</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Роль</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Статус</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Создан</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Действия</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                    @forelse ($users as $user)
                                        <tr
                                            class="user-row cursor-pointer transition hover:bg-gray-50 dark:hover:bg-white/5"
                                            data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}"
                                            data-role="{{ $user->role?->name ?? 'Не назначена' }}"
                                            data-role-id="{{ $user->role_id ?? '' }}"
                                            data-role-url="{{ route('users.role', $user) }}"
                                            data-password-url="{{ route('users.password', $user) }}"
                                            data-role-protected="{{ $user->role?->slug === 'super_admin' ? '1' : '0' }}"
                                            data-email-verified="{{ $user->email_verified_at ? 'Подтвержден' : 'Не подтвержден' }}"
                                            data-created-at="{{ $user->created_at?->format('d.m.Y H:i') }}"
                                        >
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $user->id }}</td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                                @if ($user->role?->slug === 'super_admin')
                                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700 dark:bg-red-500/10 dark:text-red-300">
                                                        {{ $user->role->name }}
                                                    </span>
                                                @else
                                                    {{ $user->role?->name ?? 'Не назначена' }}
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                                @if ($user->email_verified_at)
                                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-300">
                                                        Подтвержден
                                                    </span>
                                                @else
                                                    <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-300">
                                                        Не подтвержден
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                                {{ $user->created_at?->format('d.m.Y H:i') }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                                @if ((int) auth()->id() === (int) $user->id || $user->role?->slug === 'super_admin')
                                                    <span class="text-xs text-gray-400 dark:text-gray-500">Недоступно</span>
                                                @else
                                                    <form method="POST" action="{{ route('users.delete', $user) }}" class="js-no-modal" onsubmit="return confirm('Удалить пользователя?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            type="submit"
                                                            class="inline-flex rounded-md bg-red-600 px-3 py-2 text-xs font-medium text-white transition hover:bg-red-500"
                                                        >
                                                            Удалить
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-600 dark:text-gray-400">
                                                Пользователей пока нет.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if ($users->hasPages())
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    @endif

                    <div id="user-modal" class="fixed inset-0 z-50 hidden">
                        <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm"></div>
                        <div class="relative mx-auto mt-10 w-full max-w-3xl px-4">
                            <div class="rounded-2xl border border-white/10 bg-gray-900 text-white shadow-2xl">
                                <div class="flex items-center justify-between border-b border-white/10 px-6 py-4">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.3em] text-orange-400">Пользователь</p>
                                        <h3 class="mt-1 text-xl font-semibold">Детали пользователя</h3>
                                    </div>
                                    <button id="user-modal-close" type="button" class="rounded-md px-3 py-1 text-sm text-gray-300 hover:bg-white/10 hover:text-white">Закрыть</button>
                                </div>

                                <div class="px-6 py-5">
                                    <div id="user-data-modal" class="grid grid-cols-1 gap-4 md:grid-cols-2"></div>

                                    <div class="mt-6 border-t border-white/10 pt-5">
                                        <div id="user-role-section">
                                            <p class="text-xs uppercase tracking-wide text-gray-400">Назначение роли</p>
                                            <form id="user-role-form" method="POST" class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-center">
                                                @csrf
                                                @method('PATCH')

                                                <select
                                                    id="user-role-select"
                                                    name="role_id"
                                                    class="block w-full rounded-md border-gray-300 bg-white text-sm text-gray-900 focus:border-orange-500 focus:ring-orange-500"
                                                >
                                                    <option value="">Не назначена</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>

                                                <button
                                                    type="submit"
                                                    class="inline-flex shrink-0 rounded-md bg-orange-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-orange-600"
                                                >
                                                    Сохранить роль
                                                </button>
                                            </form>
                                        </div>

                                        <div id="user-password-section" class="mt-6">
                                            <p class="text-xs uppercase tracking-wide text-gray-400">Назначение нового пароля</p>
                                            <form id="user-password-form" method="POST" class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-2">
                                                @csrf
                                                @method('PATCH')

                                                <div>
                                                    <label for="user-password-input" class="block text-xs uppercase tracking-wide text-gray-400">
                                                        Новый пароль
                                                    </label>
                                                    <input
                                                        id="user-password-input"
                                                        name="password"
                                                        type="text"
                                                        class="mt-2 block w-full rounded-md border-gray-300 bg-white text-sm text-gray-900 focus:border-orange-500 focus:ring-orange-500"
                                                        minlength="8"
                                                        required
                                                    >
                                                </div>

                                                <div class="flex items-end">
                                                    <button
                                                        id="user-password-generate"
                                                        type="button"
                                                        class="inline-flex w-full justify-center rounded-md border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-white transition hover:bg-white/10"
                                                    >
                                                        Сгенерировать пароль
                                                    </button>
                                                </div>

                                                <div class="md:col-span-2">
                                                    <button
                                                        type="submit"
                                                        class="inline-flex rounded-md bg-orange-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-orange-600"
                                                    >
                                                        Сохранить пароль
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <div id="user-role-protected-note" class="hidden rounded-lg border border-yellow-500/30 bg-yellow-500/10 px-4 py-3 text-sm text-yellow-300">
                                            Для супер-администратора изменение роли недоступно.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
