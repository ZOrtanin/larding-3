<x-app-layout>
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
                              {{ $stats['unique_visitors'] }}
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
                              {{ $stats['page_refreshes'] }}
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
                              {{ $stats['unique_ips'] }}
                              <span class="absolute inset-0"></span>
                            </a>
                            <p class="mt-1 text-gray-400">уникальных IP</p>                            
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg dark:bg-gray-800 h-23 ">                    
                    <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-white/5">
                        <div class="mt-1 flex size-11 flex-none items-center justify-center rounded-lg bg-gray-700/50 group-hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-gray-400 group-hover:text-white" viewBox="0 0 512 512"><path d="M370 378c28.89 23.52 46 46.07 46 86M142 378c-28.89 23.52-46 46.06-46 86M384 208c28.89-23.52 32-56.07 32-96M128 206c-28.89-23.52-32-54.06-32-94M464 288.13h-80M128 288.13H48M256 192v256" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 448h0c-70.4 0-128-57.6-128-128v-96.07c0-65.07 57.6-96 128-96h0c70.4 0 128 25.6 128 96V320c0 70.4-57.6 128-128 128z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M179.43 143.52a49.08 49.08 0 01-3.43-15.73A80 80 0 01255.79 48h.42A80 80 0 01336 127.79a41.91 41.91 0 01-3.12 14.3" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>
                        </div>
                          
                          <div>
                            <a href="#" class="text-2xl font-semibold text-white">
                              {{ $stats['error_responses'] }}
                              <span class="absolute inset-0"></span>
                            </a>
                            <p class="mt-1 text-gray-400">Ответов с ошибкой</p>                            
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex w-full mx-auto pt-12">
        <div class="container max-w-7xl mx-auto px-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6 flex items-center justify-between gap-4">
                        <h2 class="text-xl font-semibold">Последние визиты</h2>
                        <form method="POST" action="{{ route('statistics.delete') }}">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                onclick="return confirm('Сбросить всю статистику?');"
                                class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-500"
                            >
                                Сбросить статистику
                            </button>
                        </form>
                    </div>
                    <div class="mx-auto">
                        <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-xl border border-gray-800">
                            <table class="w-full text-sm text-left rtl:text-right text-body">
                                <thead class="text-sm text-body bg-dark dark:bg-gray-900 border-b rounded-base border-gray-800">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-medium">ID</th>
                                        <th scope="col" class="px-6 py-3 font-medium">IP</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Method</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Status</th>
                                        <th scope="col" class="px-6 py-3 font-medium">URL</th>
                                        <th scope="col" class="px-6 py-3 font-medium">Время</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($visits as $visit)
                                        <tr
                                            class="statistics-row bg-neutral-primary border-b border-gray-900 align-top cursor-pointer hover:bg-white/5"
                                            data-id="{{ $visit->id }}"
                                            data-visitor-id="{{ $visit->visitor_id ?? '-' }}"
                                            data-ip="{{ $visit->ip ?? '-' }}"
                                            data-method="{{ $visit->method ?? '-' }}"
                                            data-status-code="{{ $visit->status_code ?? '-' }}"
                                            data-user-agent="{{ $visit->user_agent ?? '-' }}"
                                            data-browser="{{ $visit->browser ?? '-' }}"
                                            data-platform="{{ $visit->platform ?? '-' }}"
                                            data-device-type="{{ $visit->device_type ?? '-' }}"
                                            data-url="{{ $visit->url }}"
                                            data-referer="{{ $visit->referer ?? '-' }}"
                                            data-utm-source="{{ $visit->utm_source ?? '-' }}"
                                            data-utm-medium="{{ $visit->utm_medium ?? '-' }}"
                                            data-utm-campaign="{{ $visit->utm_campaign ?? '-' }}"
                                            data-utm-content="{{ $visit->utm_content ?? '-' }}"
                                            data-utm-term="{{ $visit->utm_term ?? '-' }}"
                                            data-is-mobile="{{ $visit->is_mobile ? 'Да' : 'Нет' }}"
                                            data-created-at="{{ $visit->created_at?->format('d.m.Y H:i:s') ?? '-' }}"
                                            data-updated-at="{{ $visit->updated_at?->format('d.m.Y H:i:s') ?? '-' }}"
                                        >
                                            <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">{{ $visit->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $visit->ip ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $visit->method ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $visit->status_code ?? '-' }}</td>
                                            <td class="px-6 py-4 break-all min-w-[320px]">{{ $visit->url }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $visit->created_at?->format('d.m.Y H:i:s') }}</td>
                                        </tr>
                                    @empty
                                        <tr class="bg-neutral-primary border-b border-gray-900">
                                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                                Визитов пока нет
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-white/10 px-4 py-3 sm:px-6">
                        @if ($visits->hasPages())
                            <div class="flex items-center justify-between border-t border-white/10 px-4 py-3 sm:px-6">
                                <div class="flex flex-1 justify-between sm:hidden">
                                    @if ($visits->onFirstPage())
                                        <span class="relative inline-flex items-center rounded-md border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-gray-500">Previous</span>
                                    @else
                                        <a href="{{ $visits->previousPageUrl() }}" class="relative inline-flex items-center rounded-md border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-gray-200 hover:bg-white/10">Previous</a>
                                    @endif

                                    @if ($visits->hasMorePages())
                                        <a href="{{ $visits->nextPageUrl() }}" class="relative ml-3 inline-flex items-center rounded-md border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-gray-200 hover:bg-white/10">Next</a>
                                    @else
                                        <span class="relative ml-3 inline-flex items-center rounded-md border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-gray-500">Next</span>
                                    @endif
                                </div>

                                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm text-gray-300">
                                            Showing
                                            <span class="font-medium">{{ $visits->firstItem() }}</span>
                                            to
                                            <span class="font-medium">{{ $visits->lastItem() }}</span>
                                            of
                                            <span class="font-medium">{{ $visits->total() }}</span>
                                            results
                                        </p>
                                    </div>

                                    <div>
                                        <nav aria-label="Pagination" class="isolate inline-flex -space-x-px rounded-md">
                                            @if ($visits->onFirstPage())
                                                <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-600 inset-ring inset-ring-gray-700">
                                                    <span class="sr-only">Previous</span>
                                                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5">
                                                        <path d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" fill-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @else
                                                <a href="{{ $visits->previousPageUrl() }}" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 inset-ring inset-ring-gray-700 hover:bg-white/5 focus:z-20 focus:outline-offset-0">
                                                    <span class="sr-only">Previous</span>
                                                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5">
                                                        <path d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" fill-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            @endif

                                            @foreach ($paginationItems as $item)
                                                @if (! $loop->first)
                                                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-400 inset-ring inset-ring-gray-700 focus:outline-offset-0">...</span>
                                                @endif

                                                @foreach ($item as $page => $url)
                                                    @if ($page === $visits->currentPage())
                                                        <span aria-current="page" class="relative z-10 inline-flex items-center bg-indigo-500 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">{{ $page }}</span>
                                                    @else
                                                        <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-200 inset-ring inset-ring-gray-700 hover:bg-white/5 focus:z-20 focus:outline-offset-0">{{ $page }}</a>
                                                    @endif
                                                @endforeach
                                            @endforeach

                                            @if ($visits->hasMorePages())
                                                <a href="{{ $visits->nextPageUrl() }}" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 inset-ring inset-ring-gray-700 hover:bg-white/5 focus:z-20 focus:outline-offset-0">
                                                    <span class="sr-only">Next</span>
                                                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5">
                                                        <path d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-600 inset-ring inset-ring-gray-700">
                                                    <span class="sr-only">Next</span>
                                                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5">
                                                        <path d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        @endif

                    <div id="visit-modal" class="fixed inset-0 z-50 hidden">
                        <div class="absolute inset-0 bg-black/70"></div>
                        <div class="relative flex min-h-full items-center justify-center p-4">
                            <div class="w-full max-w-4xl rounded-xl border border-gray-700 bg-gray-900 shadow-2xl">
                                <div class="flex items-center justify-between border-b border-white/10 px-6 py-4">
                                    <h3 class="text-lg font-semibold text-white">Детали визита</h3>
                                    <button id="visit-modal-close" type="button" class="rounded-md px-3 py-1 text-sm text-gray-300 hover:bg-white/10 hover:text-white">Закрыть</button>
                                </div>
                                <div id="data-modal" class="grid grid-cols-1 gap-4 px-6 py-5 md:grid-cols-2">
                                    <!--<div>
                                            <p class="text-xs uppercase tracking-wide text-gray-400">ID</p>
                                            <p id="visit-modal-id" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-400">Visitor ID</p>
                                            <p id="visit-modal-visitor-id" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-400">IP</p>
                                            <p id="visit-modal-ip" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-400">Method</p>
                                            <p id="visit-modal-method" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-400">Status</p>
                                            <p id="visit-modal-status-code" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-400">Is Mobile</p>
                                            <p id="visit-modal-is-mobile" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-400">Browser</p>
                                            <p id="visit-modal-browser" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-400">Platform</p>
                                            <p id="visit-modal-platform" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-400">Device Type</p>
                                            <p id="visit-modal-device-type" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-400">Created At</p>
                                            <p id="visit-modal-created-at" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-400">Updated At</p>
                                            <p id="visit-modal-updated-at" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div class="md:col-span-2">
                                            <p class="text-xs uppercase tracking-wide text-gray-400">URL</p>
                                            <p id="visit-modal-url" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div class="md:col-span-2">
                                            <p class="text-xs uppercase tracking-wide text-gray-400">Referer</p>
                                            <p id="visit-modal-referer" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div class="md:col-span-2">
                                            <p class="text-xs uppercase tracking-wide text-gray-400">User Agent</p>
                                            <p id="visit-modal-user-agent" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-400">UTM Source</p>
                                            <p id="visit-modal-utm-source" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-400">UTM Medium</p>
                                            <p id="visit-modal-utm-medium" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-400">UTM Campaign</p>
                                            <p id="visit-modal-utm-campaign" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-400">UTM Content</p>
                                            <p id="visit-modal-utm-content" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>
                                        <div class="md:col-span-2">
                                            <p class="text-xs uppercase tracking-wide text-gray-400">UTM Term</p>
                                            <p id="visit-modal-utm-term" class="mt-1 break-all text-sm text-white">-</p>
                                        </div>-->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
