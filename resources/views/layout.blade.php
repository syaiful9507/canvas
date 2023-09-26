<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="h-full bg-gray-900"
    @if(\Canvas\Canvas::usingRightToLeftLanguage($scripts['user']['locale'])) dir="rtl" @endif
>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name') }} ― Canvas</title>

    <link rel="icon" type="image/x-icon" href="{{ mix('img/favicon.ico', 'vendor/canvas') }}" />
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css', 'vendor/canvas') }}">
</head>
<body class="h-full">

<div id="app"></div>

<script type="text/javascript">
    window.Canvas = @json($scripts);
</script>

<script type="text/javascript" src="{{ mix('js/app.js', 'vendor/canvas') }}"></script>
</body>
</html>
