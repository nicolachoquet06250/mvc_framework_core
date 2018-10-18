<?php
    header("Content-Type: ".($content_type ?? 'application/json'));
?>

@yield('content')
