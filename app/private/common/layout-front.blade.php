<?php
    header("Content-Type: ".($content_type ?? 'text/html'));
?>
<!DOCTYPE html>
<html lang="{{ $lang ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) }}">
    <header>
        <meta charset="{{ $charset ?? 'utf-8' }}" />
        <title> {{ $title }} </title>
        @yield('before_body_script')
        @yield('before_body_css')
    </header>
    <body>
        @yield('body_content')
    </body>
    @yield('after_body_script')
</html>