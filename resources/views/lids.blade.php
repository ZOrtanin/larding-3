<x-app-layout>
    <div class="flex w-full mx-auto pt-12">
        <div class="container max-w-7xl mx-auto px-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="mb-6 text-xl font-semibold">Последние заявки</h2>
                    <div class="mx-auto">
                        <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-xl border border-gray-800">
                            <table class="w-full text-sm text-left rtl:text-right text-body">
                                <thead class="text-sm text-body bg-dark dark:bg-gray-900 border-b rounded-base border-gray-800">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-medium">ID</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Имя формы</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Статус заявки</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Время создания</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($canDeleteLeads = auth()->user()?->role?->slug !== 'manager')

                                    @forelse ($leads as $lead)
                                        <tr
                                            class="lead-row group bg-neutral-primary border-b border-gray-900 align-top cursor-pointer hover:bg-white/5"
                                            data-id="{{ $lead->id }}"
                                            data-name="{{ $lead->name }}"
                                            data-content="{{ $lead->content ?: '-' }}"
                                            data-status="{{ $lead->status }}"
                                            data-status-url="{{ route('leads.status', $lead) }}"
                                            data-delete-url="{{ $canDeleteLeads ? route('leads.delete', $lead) : '' }}"
                                            data-created-at="{{ $lead->created_at?->format('d.m.Y H:i:s') ?? '-' }}"
                                            data-updated-at="{{ $lead->updated_at?->format('d.m.Y H:i:s') ?? '-' }}"
                                            data-comments="{{ $lead->comments ?: '-' }}"
                                        >
                                            <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">{{ $lead->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $lead->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $leadStatuses[$lead->status] ?? $lead->status }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center justify-between gap-3">
                                                    <span>{{ $lead->created_at?->format('d.m.Y H:i:s') }}</span>
                                                    @if ($canDeleteLeads)
                                                        <button
                                                            type="button"
                                                            class="js-no-modal js-delete-lead inline-flex h-8 items-center rounded-md bg-red-600 px-3 text-xs font-medium text-white opacity-0 transition-opacity hover:bg-red-500 group-hover:opacity-100"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-gray-400 group-hover:text-white" viewBox="0 0 512 512"><path d="M112 112l20 320c.95 18.49 14.4 32 32 32h184c17.67 0 30.87-13.51 32-32l20-320" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M80 112h352"/><path d="M192 112V72h0a23.93 23.93 0 0124-24h80a23.93 23.93 0 0124 24h0v40M256 176v224M184 176l8 224M328 176l-8 224" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="bg-neutral-primary border-b border-gray-900">
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                                Заявок пока нет
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if ($leads->hasPages())
                        <div class="mt-6">
                            {{ $leads->links() }}
                        </div>
                    @endif

                    <div id="lead-modal" class="fixed inset-0 z-50 hidden">
                        <div class="absolute inset-0 bg-black/70"></div>
                        <div class="relative flex min-h-full items-center justify-center p-4">
                            <div class="w-full max-w-3xl rounded-xl border border-gray-700 bg-gray-900 shadow-2xl">
                                <div class="flex items-center justify-between border-b border-white/10 px-6 py-4">
                                    <h3 class="text-lg font-semibold text-white">Детали заявки</h3>
                                    <button id="lead-modal-close" type="button" class="rounded-md px-3 py-1 text-sm text-gray-300 hover:bg-white/10 hover:text-white">Закрыть</button>
                                </div>
                                <div class="px-6 py-5">
                                    <div id="lead-data-modal" class="grid grid-cols-1 gap-4 md:grid-cols-2"></div>

                                    <form id="lead-status-form" method="POST" class="mt-6 border-t border-white/10 pt-5">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex flex-col gap-4">
                                            <div>
                                                <label for="lead-comment-input" class="block text-xs uppercase tracking-wide text-gray-400">
                                                    Новый комментарий
                                                </label>
                                                <textarea
                                                    id="lead-comment-input"
                                                    name="comment"
                                                    rows="4"
                                                    class="mt-2 w-full rounded-md border border-gray-700 bg-gray-900 px-3 py-2 text-sm text-white focus:border-orange-500 focus:outline-none"
                                                    placeholder="Напишите комментарий по заявке"
                                                ></textarea>
                                            </div>

                                            <div class="flex flex-col gap-4 md:flex-row md:items-end">
                                                <div class="flex-1">
                                                <label for="lead-status-select" class="block text-xs uppercase tracking-wide text-gray-400">
                                                    Статус заявки
                                                </label>
                                                <select
                                                    id="lead-status-select"
                                                    name="status"
                                                    class="mt-2 w-full rounded-md border border-gray-700 bg-gray-900 px-3 py-2 text-sm text-white focus:border-orange-500 focus:outline-none"
                                                >
                                                    @foreach ($leadStatuses as $statusValue => $statusLabel)
                                                        <option value="{{ $statusValue }}">{{ $statusLabel }}</option>
                                                    @endforeach
                                                </select>
                                                </div>

                                                <button
                                                    type="submit"
                                                    class="rounded-md bg-orange-500 px-4 py-2 text-sm font-medium text-white hover:bg-orange-600"
                                                >
                                                    Сохранить
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
