@extends('common.layout-'.$page_type, [
    'title' => 'Erreur 404',
])

@if($page_type === \mvc_framework\core\starter\AppStarter::PAGE_API)
    @section('content')
        {
            "title": "Erreur 404",
            @if(!$message || $message !== '')
                "message": "{{ $message }}"
            @else
                "message": "Erreur 404 - Page non trouvée !"
            @endif
        }
    @endsection
@elseif($page_type === \mvc_framework\core\starter\AppStarter::PAGE_FRONT)
    @section('body_content')
        <h1> Erreur 404 </h1>
        <p>
            @if(!$message || $message !== '')
                {{ $message }}
            @else
                Page non trouvée !
            @endif
        </p>
    @endsection
@endif