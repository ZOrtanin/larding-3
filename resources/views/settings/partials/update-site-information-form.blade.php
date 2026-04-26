<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Информация о сайте') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Обновите логотип и название сайта') }}
        </p>
    </header>

    <form method="post" action="{{ route('settings.update') }}" class="mt-6 flex flex-row flex-wrap gap-0 space-x-0 space-y-6 leading-6">
        @csrf
        @method('patch')

        <div class="basis-1/2 space-y-6 pr-3">
            <div>
                <x-input-label for="site_name" :value="__('Имя сайта')" />
                <x-text-input id="site_name" name="site_name" type="text" class="mt-1 block w-full" :value="old('site_name', $settings['site_name'])" autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('site_name')" />
            </div>

            <div>
                <x-input-label for="site_description" :value="__('Описание')" />
                <x-text-input id="site_description" name="site_description" type="text" class="mt-1 block w-full" :value="old('site_description', $settings['site_description'])" />
                <x-input-error class="mt-2" :messages="$errors->get('site_description')" />
            </div>
        </div>

        <div class="basis-1/2 space-y-6 pr-3">
            <div>
                <x-input-label for="site_logo" :value="__('Логотип (URL или путь)')" />
                <x-text-input id="site_logo" name="site_logo" type="text" class="mt-1 block w-full" :value="old('site_logo', $settings['site_logo'])" />
                <x-input-error class="mt-2" :messages="$errors->get('site_logo')" />
            </div>
        </div>       

        <div class="mt-6 flex basis-full items-center justify-end gap-x-6">
            @if (session('status') === 'settings-updated-site')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Сохраняю.') }}</p>
            @endif

            <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
            
        </div>
    </form>
</section>
