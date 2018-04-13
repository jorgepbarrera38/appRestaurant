<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div id="app">
    <navbar-component csrf="{{ csrf_token() }}" username="{{ Auth::user()->name }}" userrole="{{ Auth::user()->role }}" names="{{ Auth::user()->names }}"></navbar-component>
        <router-view user="{{ Auth::user()->role }}"></router-view>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>