@extends('common.layout-'.$page_type, [
    'title' => 'Erreur 500',
])

@if($page_type === \mvc_framework\core\starter\AppStarter::PAGE_API)
    @section('content')
        {
        "title": "Erreur 500",
        @if(!$message || $message !== '')
            "message": "{{ $message }}"
        @else
            "message": "Erreur 500 - Erreur de serveur interne !"
        @endif
        }
    @endsection
@elseif($page_type === \mvc_framework\core\starter\AppStarter::PAGE_FRONT)
    @section('body_content')
        <h1> Erreur 500 </h1>
        <p>
            @if(!$message || $message !== '')
                {{ $message }}
            @else
                Erreur de serveur interne !
            @endif
        </p>
    @endsection
@endif