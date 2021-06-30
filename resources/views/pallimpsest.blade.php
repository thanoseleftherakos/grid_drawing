<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pallimpsest</title>
        <link rel="stylesheet" href="{{ mix('css/style.css') }}">
    </head>
    <body>
        <div id="app">
        <symbol-view :symbol="{{ $symbol->toJson() }}"></symbol-view>
        </div>
    </body>
    <script src="{{ mix('js/app.js') }}"></script>
</html>