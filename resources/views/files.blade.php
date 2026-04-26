<x-app-layout>
    <div class="flex w-full mx-auto pt-12">
        <div class="container max-w-7xl mx-auto px-6 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-start justify-between gap-6">
                        <div>
                            <h2 class="text-xl font-semibold">Файлы</h2>
                            <p class="mt-1 text-sm text-gray-400">Загрузка, просмотр и удаление файлов сайта.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data" class="mt-6 grid grid-cols-1 gap-4 rounded-xl border border-gray-800 bg-neutral-primary p-4 md:grid-cols-4">
                        @csrf

                        <div class="md:col-span-2">
                            <label for="file" class="block text-sm font-medium text-white">Файл</label>
                            <input id="file" name="file[]" type="file" multiple class="mt-2 block w-full rounded-md border border-white/10 bg-white/5 px-3 py-2 text-sm text-white" required />
                            <x-input-error class="mt-2" :messages="$errors->get('file')" />
                            <x-input-error class="mt-2" :messages="$errors->get('file.*')" />
                        </div>

                        <div>
                            <label for="directory" class="block text-sm font-medium text-white">Папка</label>
                            <input id="directory" name="directory" type="text" value="" class="mt-2 block w-full rounded-md border border-white/10 bg-white/5 px-3 py-2 text-sm text-white" />
                            <x-input-error class="mt-2" :messages="$errors->get('directory')" />
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-white">Описание</label>
                            <input id="description" name="description" type="text" value="{{ old('description') }}" class="mt-2 block w-full rounded-md border border-white/10 bg-white/5 px-3 py-2 text-sm text-white" />
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="md:col-span-4 flex items-center justify-between gap-4">
                            <div class="text-sm text-gray-400">
                                @if (session('status') === 'file-uploaded')
                                    Файл загружен.
                                @elseif (session('status') === 'file-deleted')
                                    Файл удалён.
                                @endif
                            </div>

                            <button type="submit" class="inline-flex rounded-xl bg-orange-500 px-4 py-2 text-sm font-medium text-white hover:bg-orange-600">
                                Загрузить файл
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-xl border border-gray-800">
                        <table class="w-full text-sm text-left text-body">
                            <thead class="text-sm text-body bg-dark dark:bg-gray-900 border-b border-gray-800">
                                <tr>
                                    <th class="px-6 py-3 font-medium">ID</th>
                                    <th class="px-6 py-3 font-medium">Тип</th>
                                    <th class="px-6 py-3 font-medium">Имя</th>
                                    <th class="px-6 py-3 font-medium">Путь</th>
                                    <th class="px-6 py-3 font-medium">Размер</th>
                                    <th class="px-6 py-3 font-medium"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($files as $file)
                                    @php($sitePath = '/'.ltrim($file->path, '/'))
                                    <tr
                                        class="file-row bg-neutral-primary border-b border-gray-900 align-top cursor-pointer hover:bg-white/5"
                                        data-id="{{ $file->id }}"
                                        data-type=".{{ strtoupper($file->extension ?: 'FILE') }}"
                                        data-name="{{ $file->original_name }}"
                                        data-path="{{ $sitePath }}"
                                        data-size="{{ number_format($file->size / 1024, 1) }} KB"
                                        data-description="{{ $file->description ?: '-' }}"
                                        data-created-at="{{ $file->created_at?->format('d.m.Y H:i') ?? '-' }}"
                                    >
                                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">{{ $file->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="file mx-auto flex h-20 w-16 justify-center rounded-lg rounded-tl-[30px] border border-gray-900 bg-dark dark:bg-slate-500">
                                                <div class="my-auto text-gray-700">.{{ strtoupper($file->extension ?: 'FILE') }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ Storage::disk($file->disk)->url($file->path) }}" target="_blank" rel="noopener noreferrer" class="font-medium text-white hover:text-orange-400">
                                                {{ $file->original_name }}
                                            </a>
                                            @if ($file->description)
                                                <p class="mt-1 text-xs text-gray-400">{{ $file->description }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-start gap-3">
                                                <button
                                                    type="button"
                                                    data-copy-text="{{ $sitePath }}"
                                                    class="js-copy-path inline-flex rounded-md bg-gray-700 px-3 py-2 text-xs font-medium text-white hover:bg-gray-600"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-400 group-hover:text-white fill-gray-400 group-hover:fill-white" viewBox="0 0 512 512"><path d="M336 64h32a48 48 0 0148 48v320a48 48 0 01-48 48H144a48 48 0 01-48-48V112a48 48 0 0148-48h32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><rect x="176" y="32" width="160" height="64" rx="26.13" ry="26.13" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/></svg>
                                                </button>
                                                <a href="{{ Storage::disk($file->disk)->url($file->path) }}" target="_blank" rel="noopener noreferrer" class="break-all text-xs text-orange-400 hover:text-orange-300 hover:underline">
                                                    {{ $sitePath }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($file->size / 1024, 1) }} KB</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <form method="POST" action="{{ route('files.delete', $file) }}" class="js-no-modal" onsubmit="return confirm('Удалить этот файл?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex rounded-md bg-red-600 px-3 py-2 text-xs font-medium text-white hover:bg-red-500">
                                                    Удалить
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-neutral-primary border-b border-gray-900">
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">Файлов пока нет</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($files->hasPages())
                        <div class="mt-6">
                            {{ $files->links() }}
                        </div>
                    @endif

                    <div id="file-modal" class="fixed inset-0 z-50 hidden">
                        <div class="absolute inset-0 bg-black/70"></div>
                        <div class="relative flex min-h-full items-center justify-center p-4">
                            <div class="w-full max-w-3xl rounded-xl border border-gray-700 bg-gray-900 shadow-2xl">
                                <div class="flex items-center justify-between border-b border-white/10 px-6 py-4">
                                    <h3 class="text-lg font-semibold text-white">Детали файла</h3>
                                    <button id="file-modal-close" type="button" class="rounded-md px-3 py-1 text-sm text-gray-300 hover:bg-white/10 hover:text-white">Закрыть</button>
                                </div>
                                <div class="px-6 py-5">
                                    <div id="file-data-modal" class="grid grid-cols-1 gap-4 md:grid-cols-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('click', async function (event) {
            const button = event.target.closest('.js-copy-path');

            if (!button) {
                return;
            }

            const originalText = button.textContent;
            const textToCopy = button.dataset.copyText || '';

            if (!textToCopy || !navigator.clipboard) {
                return;
            }

            try {
                await navigator.clipboard.writeText(textToCopy);
                button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-400 group-hover:text-white fill-gray-400 group-hover:fill-white" viewBox="0 0 512 512"><path d="M145.61 464h220.78c19.8 0 35.55-16.29 33.42-35.06C386.06 308 304 310 304 256s83.11-51 95.8-172.94c2-18.78-13.61-35.06-33.41-35.06H145.61c-19.8 0-35.37 16.28-33.41 35.06C124.89 205 208 201 208 256s-82.06 52-95.8 172.94c-2.14 18.77 13.61 35.06 33.41 35.06z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M343.3 432H169.13c-15.6 0-20-18-9.06-29.16C186.55 376 240 356.78 240 326V224c0-19.85-38-35-61.51-67.2-3.88-5.31-3.49-12.8 6.37-12.8h142.73c8.41 0 10.23 7.43 6.4 12.75C310.82 189 272 204.05 272 224v102c0 30.53 55.71 47 80.4 76.87 9.95 12.04 6.47 29.13-9.1 29.13z"/></svg>';
            } catch (error) {
                button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-400 group-hover:text-white" viewBox="0 0 512 512"><path d="M370 378c28.89 23.52 46 46.07 46 86M142 378c-28.89 23.52-46 46.06-46 86M384 208c28.89-23.52 32-56.07 32-96M128 206c-28.89-23.52-32-54.06-32-94M464 288.13h-80M128 288.13H48M256 192v256" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 448h0c-70.4 0-128-57.6-128-128v-96.07c0-65.07 57.6-96 128-96h0c70.4 0 128 25.6 128 96V320c0 70.4-57.6 128-128 128z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M179.43 143.52a49.08 49.08 0 01-3.43-15.73A80 80 0 01255.79 48h.42A80 80 0 01336 127.79a41.91 41.91 0 01-3.12 14.3" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>';
            }

            window.setTimeout(function () {
                button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-400 group-hover:text-white fill-gray-400 group-hover:fill-white" viewBox="0 0 512 512"><path d="M336 64h32a48 48 0 0148 48v320a48 48 0 01-48 48H144a48 48 0 01-48-48V112a48 48 0 0148-48h32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><rect x="176" y="32" width="160" height="64" rx="26.13" ry="26.13" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/></svg>';
            }, 1500);
        });
    </script>
</x-app-layout>
