<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield("title")</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="{{asset("css/app.css")}}" rel="stylesheet">

</head>
<body>
<div class="container">
    <div id="root">
        @yield('content')
    </div>
</div>
<script src="{{asset("js/app.js")}}"></script>
</body>
</html>
