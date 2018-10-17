@extends('common.layout', [
    'title' => 'Hello World'
])

@section('content')
    @if(isset($argv))
        "argv" : {!! json_encode($argv) !!}
    @else
        "argv" : null
    @endif
@endsection