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

            <div>
                <x-input-label for="mail_from_address" :value="__('Отправитель Email')" />
                <x-text-input id="mail_from_address" name="mail_from_address" type="email" class="mt-1 block w-full" :value="old('mail_from_address', $settings['mail_from_address'])" />
                <x-input-error class="mt-2" :messages="$errors->get('mail_from_address')" />
            </div>

            <div>
                <x-input-label for="orders_notification_email" :value="__('Email для заказов')" />
                <x-text-input id="orders_notification_email" name="orders_notification_email" type="email" class="mt-1 block w-full" :value="old('orders_notification_email', $settings['orders_notification_email'])" />
                <x-input-error class="mt-2" :messages="$errors->get('orders_notification_email')" />
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

            <div>
                <x-input-label for="mail_from_name" :value="__('Имя отправителя')" />
                <x-text-input id="mail_from_name" name="mail_from_name" type="text" class="mt-1 block w-full" :value="old('mail_from_name', $settings['mail_from_name'])" />
                <x-input-error class="mt-2" :messages="$errors->get('mail_from_name')" />
            </div>
        </div>

        <div class="mt-6 flex basis-full flex-wrap items-center justify-end gap-x-6 gap-y-4">
            @if (session('status') === 'settings-updated-mail')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Сохраняю.') }}</p>
            @endif

            @if (session('status') === 'mail-test-sent')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-emerald-600 dark:text-emerald-400"
                >{{ __('Тестовое письмо отправлено.') }}</p>
            @endif

            @if (session('cms_mail_error'))
                <p class="text-sm text-red-600 dark:text-red-400">{{ session('cms_mail_error') }}</p>
            @endif

            <x-primary-button type="submit" form="mail-test-form">{{ __('Тестовое письмо') }}</x-primary-button>

            <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
            
        </div>
    </form>

    <form id="mail-test-form" method="post" action="{{ route('settings.test-mail') }}" class="hidden">
        @csrf
    </form>
</section>
