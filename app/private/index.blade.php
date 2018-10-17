@extends('common.layout', [
    'title' => 'Hello World'
])

@section('content')
    "argv" : {!! json_encode($argv) !!}
    {{--<div>--}}
        {{--My name is Nicolas !!!--}}
    {{--</div>--}}
@endsection