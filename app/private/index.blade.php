@extends('common.layout-api', [
    'title' => 'Hello World'
])

@section('content')
    {
        "title": "Hello World",
    @if(isset($argv))
        "argv" : {!! json_encode($argv) !!}
    @else
        "argv" : null
    @endif
    @if(isset($argv))
        , "api" : {!! json_encode($http) !!}
    @else
        , "api" : null
    @endif
    }
@endsection