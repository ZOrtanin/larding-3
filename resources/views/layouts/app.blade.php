<!DOCTYPE html>
<html lang="zxx">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="{{ $siteDescription ?: config('app.name', 'Laravel') }}">

        <title>{{ $siteName ?: config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link href="{{ asset('js/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="{{ asset('js/lib/tempusdominus/js/moment.min.js') }}" defer></script>
        <script src="{{ asset('js/lib/tempusdominus/js/moment-timezone.min.js') }}" defer></script>
        <script src="{{ asset('js/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}" defer></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

            @guest
                
            @else
                <script src="{{ asset('js/lib/chart/chart.min.js') }}" defer></script>
                <script src="{{ asset('js/admin.js') }}" defer></script>
                <input type="hidden" id="url_block_create" value="{{ route('settings.create') }}">
                <input type="hidden" id="url_block_show" value="{{ route('settings.blocks.show', ['block' => '__BLOCK__']) }}">
                <input type="hidden" id="url_block_templates" value="{{ route('settings.templates.index') }}">
                <input type="hidden" id="url_block_template_show" value="{{ route('settings.templates.show', ['blockTemplate' => '__TEMPLATE__']) }}">
                <input type="hidden" id="url_block_delete" value="{{ route('settings.blocks.delete', ['block' => '__BLOCK__']) }}">
                <input type="hidden" id="url_block_up" value="{{ route('settings.blocks.up', ['block' => '__BLOCK__']) }}">
                <input type="hidden" id="url_block_down" value="{{ route('settings.blocks.down', ['block' => '__BLOCK__']) }}">
                <input type="hidden" id="url_block_visibility" value="{{ route('settings.blocks.visibility', ['block' => '__BLOCK__']) }}">

                @include('layouts.navigation')

                <div class="h-[64px] w-full"></div>

            @endguest

            <!-- Page Heading -->
            @isset($header)
                <!-- <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header> -->
            @endisset

            <!-- Page Content -->
            <!-- <main> -->
                {{ $slot }}
            <!-- </main> -->
        </div>
    </body>
</html>
