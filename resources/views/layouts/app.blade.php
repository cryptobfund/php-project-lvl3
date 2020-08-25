<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta charset="UTF-8">
</head>
<body>
    {{--@include('navbar')--}}
    <div class="container-fluid">
        <a href="{{route('welcome')}}">Home</a>
        <a href="{{route('domains.index')}}">Domains</a>
        <div class="jumbotron">
            @yield('content')
        </div>
    </div>
</body>
<footer class="fixed-bottom">
    <hr/>
    <div class="container text-center mt-5 pb-3">
        created by <a target="_blank" href="https://github.com/cryptobfund">Johnik</a>
    </div>
</footer>
</html>
