<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Правила валидации полей') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Добавляйте или редактируйте уже существующие') }}
        </p>
    </header>

    <form method="post" action="{{ route('settings.update') }}" class="mt-6 flex flex-row flex-wrap gap-0 space-x-0 space-y-6 leading-6">
        @csrf
        @method('patch') 
        <div class="basis-full space-y-6 pr-3">
            <div>
                
                <x-input-label for="validation_rules" :value="__('Правила валидации')" />          

                <x-text-area id="validation_rules" name="validation_rules" type="text" class="mt-1 block w-full h-[300px]" :value="old('validation_rules', $settings['validation_rules'])" />
                
                <x-input-error class="mt-2" :messages="$errors->get('validation_rules')" />
            </div>
        </div>

        <div class="mt-6 flex basis-full items-center justify-end gap-x-6">
            @if (session('status') === 'settings-updated-validation')
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
