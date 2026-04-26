<x-app-layout>
    <!-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> -->

    
    
    <!-- Заявки -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="mb-6">Название сайта 2</h2>
                    
                </div>
            </div> -->

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="">
                    @include('settings.partials.update-site-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="">
                    @include('settings.partials.validation-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="">
                    @include('settings.partials.settings-mail-server-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="">
                    @include('settings.partials.settings-updates-form')
                </div>
            </div>

        </div>
    </div>

    

    <div class="flex w-full mx-auto pt-6 h-10"></div>

<link href="{{ asset('js/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{ asset('js/lib/tempusdominus/js/moment.min.js') }}" defer></script>
<script src="{{ asset('js/lib/tempusdominus/js/moment-timezone.min.js') }}" defer></script>
<script src="{{ asset('js/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}" defer></script>
<script src="{{ asset('js/main.js') }}" defer></script>

</x-app-layout>
