<?php
    header('Content-Type: '.$content_type);
?>

{
    "title": "{{ $title }}",
    @yield('content')
}