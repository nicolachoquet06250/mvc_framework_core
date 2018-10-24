<div class="doc-block col-{{ $col ?? 12 }} offset-{{ $offset ?? 0 }} {{ $supplement_class }}">
    <div class="card mt-2">
        @if(isset($class_doc))
            <div class="card-header">
                <div class="row">
                    @if(isset($class_doc['doc']) && isset($class_doc['doc']['title']))
                        <div class="col-12">
                            <h2 class="card-title">
                                {{ $class_doc['doc']['title'] }}
                            </h2>
                        </div>
                    @endif
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-12 col-sm-2 font-weight-bold">
                        Class
                    </div>
                    <div class="col-12 col-sm-10">
                        {{ $class_doc['namespace'] }}\{{ $class_doc['name'] }}
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(isset($class_doc['doc']) && isset($class_doc['doc']['description']))
                    <p class="card-text">
                        {!! str_replace("\n", '<br>', $class_doc['doc']['description']) !!}
                    </p>
                @endif
                @if(isset($class_doc['doc']) && isset($class_doc['doc']['code-demo']))
                    @if($class_doc['doc']['code-demo']['type'] === 'url')
						<?php
						$class_doc['doc']['code-demo']['type'] = 'http';
						$class_doc['doc']['code-demo']['code'] = explode("\n", $class_doc['doc']['code-demo']['code']);
						foreach ($class_doc['doc']['code-demo']['code'] as $id => $code) {
							$class_doc['doc']['code-demo']['code'][$id] = (!isset($_SERVER['HTTPS']) || (isset($_SERVER['HTTPS']) && is_null($_SERVER['HTTPS'])) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'].$code;
						}
						$class_doc['doc']['code-demo']['code'] = implode("\n", $class_doc['doc']['code-demo']['code']);
						?>
                    @endif
                    <pre class="code-view"><code
                                class="language-{{ $class_doc['doc']['code-demo']['type'] }} line-numbers">{{ $class_doc['doc']['code-demo']['code'] }}</code></pre>
                @endif
                <hr>
                @if($methods_doc)
                    @foreach($methods_doc as $method => $doc)
                        <div class="card mt-1">
                            <div class="card-header">
                                <h3 class="card-title">
                                    @if(isset($doc['title']))
                                        {{ $doc['title'] }}
                                    @else
                                        {{ $method }}
                                    @endif
                                </h3>
                            </div>
                            <div class="card-body">
                                @if(isset($doc['description']))
                                    <p class="card-text">
                                        {!! str_replace("\n", '<br>', $doc['description']) !!}
                                    </p>
                                @endif

                                @if(isset($doc['code-demo']))
                                    @if($doc['code-demo']['type'] === 'url')
										<?php
										$doc['code-demo']['type'] = 'http';
										$doc['code-demo']['code'] = explode("\n", $doc['code-demo']['code']);
										foreach ($doc['code-demo']['code'] as $id => $code) {
											$doc['code-demo']['code'][$id] = (!isset($_SERVER['HTTPS']) || (isset($_SERVER['HTTPS']) && is_null($_SERVER['HTTPS'])) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'].$doc['code-demo']['code'][$id];
										}
										$doc['code-demo']['code'] = implode("\n", $doc['code-demo']['code']);
										?>
                                    @endif
                                    <pre class="code-view"><code
                                                class="language-{{ $doc['code-demo']['type'] }} line-numbers">{{ $doc['code-demo']['code'] }}</code></pre>
                                @endif
                            </div>
                            @if(isset($doc['author']))
								<?php $author = $doc['author']; ?>
                            @elseif(isset($class_doc['doc']['author']))
								<?php $author = $class_doc['doc']['author']; ?>
                            @else
								<?php $author = null; ?>
                            @endif

                            @if(isset($doc['date']))
								<?php $date = $doc['date']; ?>
                            @elseif(isset($class_doc['doc']['date']))
								<?php $date = $class_doc['doc']['date']; ?>
                            @else
								<?php $date = null; ?>
                            @endif

                            @if(!is_null($author) && !is_null($date))
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-12 col-sm-6 text-center bold">
                                            {{ $author }}
                                        </div>
                                        <div class="col-12 col-sm-6 text-center bold">
                                            {{ $date }}
                                        </div>
                                    </div>
                                </div>
                            @elseif(is_null($author) && !is_null($date))
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-12 text-center bold">
                                            {{ $date }}
                                        </div>
                                    </div>
                                </div>
                            @elseif(!is_null($author) && is_null($date))
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-12 text-center bold">
                                            {{ $author }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        @elseif(isset($route_doc))
            <div class="card-header">
                @if(isset($route_doc['title']))
                    <div class="col-12">
                        <h2 class="card-title">
                            {{ $route_doc['title'] }}
                        </h2>
                    </div>
                @endif
            </div>
            <div class="card-body">
                @if(isset($route_doc['description']))
                    <p class="card-text">
                        {!! str_replace("\n", '<br>', $route_doc['description']) !!}
                    </p>
                @endif
                @if(isset($route_doc['code-demo']))
                    @if($route_doc['code-demo']['type'] === 'url')
						<?php
						$route_doc['code-demo']['type'] = 'http';
						$route_doc['code-demo']['code'] = explode("\n", $route_doc['code-demo']['code']);
						foreach ($route_doc['code-demo']['code'] as $id => $code) {
							$route_doc['code-demo']['code'][$id] = (!isset($_SERVER['HTTPS']) || (isset($_SERVER['HTTPS']) && is_null($_SERVER['HTTPS'])) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'].$route_doc['code-demo']['code'][$id];
						}
						$route_doc['code-demo']['code'] = implode("\n", $route_doc['code-demo']['code']);
						?>
                    @endif
                    <pre class="code-view"><code
                                class="language-{{ $route_doc['code-demo']['type'] }} line-numbers">{{ $route_doc['code-demo']['code'] }}</code></pre>
                @endif
            </div>
            @if(isset($route_doc['author']))
				<?php $author = $route_doc['author']; ?>
            @else
				<?php $author = null; ?>
            @endif

            @if(isset($route_doc['date']))
				<?php $date = $route_doc['date']; ?>
            @else
				<?php $date = null; ?>
            @endif

            @if(!is_null($author) && !is_null($date))
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center bold">
                            {{ $author }}
                        </div>
                        <div class="col-12 col-sm-6 text-center bold">
                            {{ $date }}
                        </div>
                    </div>
                </div>
            @elseif(is_null($author) && !is_null($date))
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center bold">
                            {{ $date }}
                        </div>
                    </div>
                </div>
            @elseif(!is_null($author) && is_null($date))
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center bold">
                            {{ $author }}
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>