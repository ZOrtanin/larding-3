<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('favicon.ico') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Загрузите одно изображение, а CMS сама соберёт favicon.ico, PNG-версии и mobile icons.') }}
        </p>
    </header>

    <form method="post" action="{{ route('settings.update-favicon') }}" enctype="multipart/form-data" class="mt-6 flex flex-row flex-wrap gap-0 space-x-0 space-y-6 leading-6">
        @csrf
        @method('patch')
        
        <div class="basis-1/2 space-y-6 pr-3">

            <div>
                <x-input-label for="site_favicon" :value="__('Favicon (изображение)')" />
                <div class="mt-1 flex flex-col gap-4 md:flex-row md:items-start">
                    <input id="site_favicon" name="site_favicon" type="file" accept="image/png,image/jpeg,image/webp,image/gif" class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" />
                    
                </div>
                @if ($settings['site_favicon_source'])
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        {{ __('Текущий исходник:') }} {{ $settings['site_favicon_source'] }}
                    </p>
                @endif
                <x-input-error class="mt-2" :messages="$errors->get('site_favicon')" />
            </div>
        </div>

        <div class="basis-1/2 space-y-6 pr-3">
            @if ($settings['site_favicon_source'])                
                <img
                    src="{{ asset('storage/'.$settings['site_favicon_source']) }}"
                    alt="Текущая favicon"
                    class="h-16 w-16 rounded-lg border border-gray-200 bg-white object-cover dark:border-gray-700 dark:bg-gray-800"
                >                
            @endif
        </div>       

        <div class="mt-6 flex basis-full items-center justify-end gap-x-6">
            @if (session('status') === 'settings-updated-favicon')
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
