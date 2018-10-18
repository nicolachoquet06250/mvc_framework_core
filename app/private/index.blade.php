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
    }
@endsection