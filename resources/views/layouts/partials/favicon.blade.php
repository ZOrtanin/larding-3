@php($favicon = \App\Support\FaviconAssets::urls())
<meta name="theme-color" content="#1a120d">
<meta name="msapplication-TileColor" content="#1a120d">
<link rel="icon" href="{{ $favicon['ico'] }}" sizes="any">
<link rel="icon" type="image/png" sizes="32x32" href="{{ $favicon['png32'] }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ $favicon['png16'] }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ $favicon['apple'] }}">
<link rel="manifest" href="{{ $favicon['manifest'] }}">
<meta name="msapplication-config" content="{{ $favicon['browserconfig'] }}">
