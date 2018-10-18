@extends('common.layout-front', [
    'title' => 'Formulaire',
])

@section('body_content')
    <form method="post" action="/HelloWorld">
        <input type="text" name="toto" />
        <br>
        <input type="submit" value="Envoyer" />
    </form>
    @component('components.doc_block')
        coucou toi
    @endcomponent
@endsection