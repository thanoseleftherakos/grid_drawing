<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Flag</title>
        <link rel="stylesheet" href="{{ mix('css/style.css') }}">
    </head>
    <body>
        <div id="app">
            <symbol-tool></symbol-tool>
        </div>
    </body>
    <script src="https://kit.fontawesome.com/c6adaa43ec.js" crossorigin="anonymous"></script>
    <script src="{{ mix('js/app.js') }}"></script>
</html>