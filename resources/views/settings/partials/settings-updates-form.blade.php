<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Обновление') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('У вас установленная последняя версия') }}
        </p>
    </header>

    <form method="post" action="{{ route('settings.update') }}" class="mt-6 flex flex-row flex-wrap gap-0 space-x-0 space-y-6 leading-6">
        @csrf
        @method('patch')

        <div class="basis-1/2 space-y-6 pr-3">
        </div>

        <div class="mt-6 flex basis-full items-center justify-end gap-x-6">

            @if (session('status') === 'settings-updated-update')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Сохраняю.') }}</p>
            @endif

            <x-primary-button>{{ __('Обновить') }}</x-primary-button>

            
        </div>
    </form>
</section>
