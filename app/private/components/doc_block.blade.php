<div class="block">
    {{ $author }}<br><br>
    {{ $date }}<br><br>
    {{ $_title }}<br><br>
    {!! str_replace("\n", '<br>', $description) !!}<br><br>
    @foreach($modifiers as $class => $title)
        {{ $class }} => {{ $title }} <br><br>
    @endforeach
    {!! $code_demo !!}<br><br>
    Je suis un block<br><br>
    <br><br>
    {{ $slot }}
</div>