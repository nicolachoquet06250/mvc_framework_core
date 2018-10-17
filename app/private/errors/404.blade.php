@extends('common.layout', [
    'title' => 'Erreur 404',
    'content_type' => 'application/json',
])

@section('content')
    "message": "Erreur 404 - Page non trouvée !"
@endsection