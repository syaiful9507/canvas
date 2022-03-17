<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    @if(\Canvas\Canvas::usingRightToLeftLanguage($jsVars['user']['locale'])) dir="rtl" @endif
    @class([
        'h-full' => true,
        'dark' => \Canvas\Canvas::enabledDarkMode($jsVars['user']['dark_mode'])
    ])
>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} ― Canvas</title>

    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="//fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css', 'vendor/canvas') }}">

    @if(\Canvas\Canvas::enabledDarkMode($jsVars['user']['dark_mode']))
        <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.5.0/build/styles/sunburst.min.css">
    @else
        <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.5.0/build/styles/github.min.css">
    @endif

    <script src="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.5.0/build/highlight.min.js"></script>
</head>
<body class="h-full">

@if(!\Canvas\Canvas::assetsUpToDate())
    <div class="alert alert-danger border-0 text-center rounded-0 mb-0">
        {{ trans('canvas::app.assets_are_not_up_to_date') }}
        {{ trans('canvas::app.to_update_run') }}<br/><code>php artisan canvas:publish</code>
    </div>
@endif

<div id="app"></div>

<script type="text/javascript">
    window.Canvas = @json($jsVars);
</script>

<script type="text/javascript" src="{{ mix('js/app.js', 'vendor/canvas') }}"></script>
</body>
</html>
