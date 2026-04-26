<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Почта') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Настройте почтовый ящик для отправки и получения писем') }}
        </p>
    </header>

    <form method="post" action="{{ route('settings.update') }}" class="mt-6 flex flex-row flex-wrap gap-0 space-x-0 space-y-6 leading-6">
        @csrf
        @method('patch')

        <div class="basis-1/2 space-y-6 pr-3">
            <div>
                <x-input-label for="mail_driver" :value="__('Драйвер')" />
                <x-text-input id="mail_driver" name="mail_driver" type="text" class="mt-1 block w-full" :value="old('mail_driver', $settings['mail_driver'])" />
                <x-input-error class="mt-2" :messages="$errors->get('mail_driver')" />
            </div>

            <div>
                <x-input-label for="mail_host" :value="__('Хост')" />
                <x-text-input id="mail_host" name="mail_host" type="text" class="mt-1 block w-full" :value="old('mail_host', $settings['mail_host'])" />
                <x-input-error class="mt-2" :messages="$errors->get('mail_host')" />
            </div>

            <div>
                <x-input-label for="mail_port" :value="__('Порт')" />
                <x-text-input id="mail_port" name="mail_port" type="text" class="mt-1 block w-full" :value="old('mail_port', $settings['mail_port'])" />
                <x-input-error class="mt-2" :messages="$errors->get('mail_port')" />
            </div>
        </div>

        <div class="basis-1/2 space-y-6 pr-3">
            <div>
                <x-input-label for="mail_username" :value="__('Имя пользователя')" />
                <x-text-input id="mail_username" name="mail_username" type="text" class="mt-1 block w-full" :value="old('mail_username', $settings['mail_username'])" />
                <x-input-error class="mt-2" :messages="$errors->get('mail_username')" />
            </div>

            <div>
                <x-input-label for="mail_password" :value="__('Пароль')" />
                <x-text-input id="mail_password" name="mail_password" type="text" class="mt-1 block w-full" :value="old('mail_password', $settings['mail_password'])" />
                <x-input-error class="mt-2" :messages="$errors->get('mail_password')" />
            </div>

            <div>
                <x-input-label for="mail_encryption" :value="__('Кодирование')" />
                <x-text-input id="mail_encryption" name="mail_encryption" type="text" class="mt-1 block w-full" :value="old('mail_encryption', $settings['mail_encryption'])" />
                <x-input-error class="mt-2" :messages="$errors->get('mail_encryption')" />
            </div>
        </div>

        <div class="mt-6 flex basis-full items-center justify-end gap-x-6">
            @if (session('status') === 'settings-updated-mail')
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
