{{--<!DOCTYPE html>--}}
{{--<html>--}}
    {{--<header>--}}
        {{--<meta charset="utf-8" />--}}
        {{--<title> {{ $title }} </title>--}}
    {{--</header>--}}
    {{--<body>--}}
        {{--<h1> {{ $title }} </h1>--}}
        {{--<section>--}}
            {{--@yield('content')--}}
        {{--</section>--}}
    {{--</body>--}}
{{--</html>--}}
<?php
    header('Content-Type: '.$content_type);
?>

{
    "title": "{{ $title }}",
    @yield('content')
}