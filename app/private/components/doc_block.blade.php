@if($doc_part['author'] && $doc_part['date'] && $doc_part['title'] && $doc_part['description'] && $doc_part['code_demo'])
    <div class="doc-block col-{{ $col ?? 12 }} offset-{{ $offset ?? 0 }} {{ $supplement_class }}">
        <div class="card">
            <div class="card-header text-center">
                <h5 class="card-title"> {{ $doc_part['title'] }} </h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    {!! str_replace("\n", '<br>', $doc_part['description']) !!}
                </p>
                @if(isset($doc_part['code_demo']) && $doc_part['code_demo'] !== null && $doc_part['code_demo'] !== '')
                    @if(isset($doc_part['modifiers']) && !empty($doc_part['modifiers']))
                        <ul class="modifiers list-group list-group-flush">
                            <li class="list-group-item">
                                <h5 class="card-title"> Défault </h5>
                                <hr>
                                <div class="code-demo">
                                    {!! str_replace('{class_modifier}', '', $doc_part['code_demo']) !!}
                                </div>
                            </li>
                            @foreach($doc_part['modifiers'] as $class => $title)
                                <li class="list-group-item">
                                    <h5 class="card-title"> {{ $title }} </h5>
                                    <hr>
                                    <div class="code-demo">
                                        {!! str_replace('{class_modifier}', str_replace('.', '', $class), $doc_part['code_demo']) !!}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <b> Démo : </b>
                        <hr>
                        <div class="code-demo">
                            {!! $doc_part['code_demo'] !!}
                        </div>
                    @endif
                    <br>
                    <b> Code : </b>
                    <hr>
                    <pre class="code-view"><code class="language-markup line-numbers">{{ $doc_part['code_demo'] }}</code></pre>
                @endif
                <hr>
                <div class="row">
                    <div class="col-12 col-sm-2 col-md-1">Fichier: </div>
                    <div class="col-12 col-sm-10 col-md-11 text-muted text-center">{{ $doc_part['file'] }}</div>
                    <div class="col-6 text-center">Auteur: {{ $doc_part['author'] }}</div>
                    <div class="col-6 text-center">Date: {{ $doc_part['date'] }}</div>
                </div>
            </div>
        </div>
    </div>
@endif