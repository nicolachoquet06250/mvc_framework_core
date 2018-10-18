@extends('common.layout-api', [
    'title' => 'Erreur 404',
])

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