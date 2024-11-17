<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
          <meta charset="UTF-8">
          <link rel="icon" href="{{asset('icon.png')}}">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title></title>
          <link href="https://fonts.cdnfonts.com/css/lato" rel="stylesheet">
          @vite('resources/css/app.css')

    </head>
    <body>
        <div id="app"></div>
        @vite('resources/js/main.js')
    </body>
</html>
