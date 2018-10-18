@extends('common.layout-api', [
    'title' => 'HelloWorld api'
])

@section('content')
    {
        "toto": "{{ $toto }}"
    }
@endsection