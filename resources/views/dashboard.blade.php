<x-app-layout>
    <!-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> -->

    
    <div class="flex w-full mx-auto pt-12">
        <div class="container max-w-7xl mx-auto px-6 ">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-gray-900 dark:text-gray-100">
                <div class="bg-white rounded-lg dark:bg-gray-800 h-23 ">
                    <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-white/5">
                          <div class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-700/50 group-hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-gray-400 group-hover:text-white" viewBox="0 0 512 512"><circle fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" cx="256" cy="56" r="40"/><path fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" d="M204.23 274.44c2.9-18.06 4.2-35.52-.5-47.59-4-10.38-12.7-16.19-23.2-20.15L88 176.76c-12-4-23.21-10.7-24-23.94-1-17 14-28 29-24 0 0 88 31.14 163 31.14s162-31 162-31c18-5 30 9 30 23.79 0 14.21-11 19.21-24 23.94l-88 31.91c-8 3-21 9-26 18.18-6 10.75-5 29.53-2.1 47.59l5.9 29.63 37.41 163.9c2.8 13.15-6.3 25.44-19.4 27.74S308 489 304.12 476.28l-37.56-115.93q-2.71-8.34-4.8-16.87L256 320l-5.3 21.65q-2.52 10.35-5.8 20.48L208 476.18c-4 12.85-14.5 21.75-27.6 19.46s-22.4-15.59-19.46-27.74l37.39-163.83z"/></svg>
                          </div>
                          <div>
                            <a href="#" class="text-2xl font-semibold text-white">
                              {{ $uniqueVisitors }}
                              <span class="absolute inset-0"></span>
                            </a>
                            <p class="mt-1 text-gray-400">Уникалов за все время</p>                            
                          </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg dark:bg-gray-800 h-23 ">
                    <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-white/5">
                          <div class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-700/50 group-hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-gray-400 group-hover:text-white" viewBox="0 0 512 512"><path d="M434.67 285.59v-29.8c0-98.73-80.24-178.79-179.2-178.79a179 179 0 00-140.14 67.36m-38.53 82v29.8C76.8 355 157 435 256 435a180.45 180.45 0 00140-66.92" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M32 256l44-44 46 44M480 256l-44 44-46-44"/></svg>
                          </div>
                          
                          <div>
                            <a href="#" class="text-2xl font-semibold text-white">
                              {{ $pageRefreshes }}
                              <span class="absolute inset-0"></span>
                            </a>
                            <p class="mt-1 text-gray-400">Обновлений страниц</p>                            
                          </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg dark:bg-gray-800 h-23 ">                
                    <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-white/5">
                        <div class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-700/50 group-hover:bg-gray-700">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-gray-400 group-hover:text-white" viewBox="0 0 512 512"><rect x="48" y="96" width="416" height="304" rx="32.14" ry="32.14" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M16 416h480"/></svg>
                        </div>
                          
                        <div>
                            <a href="#" class="text-2xl font-semibold text-white">
                              {{ $uniqueIps }}
                              <span class="absolute inset-0"></span>
                            </a>
                            <p class="mt-1 text-gray-400">уникальных IP</p>                            
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg dark:bg-gray-800 h-23 ">                    
                    <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-white/5">
                        <div class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-700/50 group-hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-gray-400 group-hover:text-white" viewBox="0 0 512 512"><circle cx="176" cy="416" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><circle cx="400" cy="416" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M48 80h64l48 272h256"/><path d="M160 288h249.44a8 8 0 007.85-6.43l28.8-144a8 8 0 00-7.85-9.57H128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>
                        </div>
                          
                          <div>
                            <a href="#" class="text-2xl font-semibold text-white">
                              {{ $leadsCount }}
                              <span class="absolute inset-0"></span>
                            </a>
                            <p class="mt-1 text-gray-400">Всего лидов</p>                            
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Заявки -->
    <div class="flex w-full mx-auto pt-6">
        <div class="container max-w-7xl mx-auto px-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="mb-6">Последние заявки</h2>
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

                                    @forelse ($latestLeads as $lead)
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

    <div class="flex w-full mx-auto pt-6">
        <div class="container max-w-7xl mx-auto px-6 ">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-gray-900 dark:text-gray-100">
                <div class="bg-white rounded-lg dark:bg-gray-800 h-90 p-6">
                    <h2 class="mb-6">Последние IP</h2>

                    <div class="mx-auto">
                        <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-xl border border-gray-800">
                            <table class="w-full text-sm text-left rtl:text-right text-body">
                                <thead class="text-sm text-body bg-dark dark:bg-gray-900 border-b rounded-base border-gray-800">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-medium">
                                            id
                                        </th>
                                        <th scope="col" class="px-6 py-3 font-medium">
                                            ip
                                        </th>
                                        <th scope="col" class="px-6 py-3 font-medium">
                                            Время
                                        </th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($latestIps as $visit)
                                        <tr class="bg-neutral-primary border-b border-gray-900">
                                            <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                                                {{ $visit->id }}
                                            </th>
                                            <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                                                {{ $visit->ip }}
                                            </th>
                                            <td class="px-6 py-4">
                                                {{ $visit->created_at?->format('d.m.Y H:i:s') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="bg-neutral-primary border-b border-gray-900">
                                            <td colspan="3" class="px-6 py-8 text-center text-gray-400">
                                                IP-адресов пока нет
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="bg-white rounded-lg dark:bg-gray-800 h-90 p-6">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <h2>Календарь событий</h2>
                        <button
                            id="calendar-today-button"
                            type="button"
                            class="rounded-full border border-white/10 px-3 py-1 text-xs font-medium text-gray-300 transition hover:border-orange-500/50 hover:text-white"
                        >
                            Сегодня
                        </button>
                    </div>
                    <div
                        id="calender"
                        data-leads='@json($leadCalendarMap)'
                        class="rounded-2xl border border-white/10 bg-gray-900/40 p-4"
                    >
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <button
                                id="calendar-prev-button"
                                type="button"
                                class="calendar-nav-button"
                                aria-label="Предыдущий месяц"
                            ></button>
                            <div id="calendar-current-label" class="text-sm font-semibold uppercase tracking-[0.18em] text-white"></div>
                            <button
                                id="calendar-next-button"
                                type="button"
                                class="calendar-nav-button"
                                aria-label="Следующий месяц"
                            ></button>
                        </div>
                        <div id="calendar-weekdays" class="mb-3 grid grid-cols-7 gap-2"></div>
                        <div id="calendar-grid" class="grid grid-cols-7 gap-2"></div>
                    </div>
                    <div id="calendar-leads-panel" class="mt-5 rounded-xl border border-white/10 bg-gray-900/40 p-4">
                        <div id="calendar-leads-title" class="text-sm font-medium text-white">
                            Выберите день
                        </div>
                        <div id="calendar-leads-empty" class="mt-2 text-sm text-gray-400">
                            Нажмите на дату в календаре, чтобы посмотреть лиды.
                        </div>
                        <ul id="calendar-leads-list" class="mt-3 hidden space-y-2"></ul>
                    </div>
                </div>
                <div class="bg-white rounded-lg dark:bg-gray-800 h-90 ">
                    <div class="flex items-center justify-between gap-4 p-6 pb-0">
                        <h2 class="mb-6">График посещений</h2>
                        <select
                            id="visits-chart-metric"
                            class="mb-6 rounded-full border border-white/10 bg-gray-900 px-4 py-2 text-sm text-gray-200 focus:border-orange-500 focus:outline-none"
                        >
                            <option value="visits">Посещения</option>
                            <option value="refreshes">Обновления страниц</option>
                            <option value="leads">Лиды</option>
                        </select>
                    </div>
                    <div class="px-6 pb-6">
                        <canvas
                            id="visits-chart"
                            data-series='@json($visitsChartSeries)'
                            height="260"
                        ></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex w-full mx-auto pt-6 h-10"></div>



</x-app-layout>
