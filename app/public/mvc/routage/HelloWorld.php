<?php

use mvc_framework\app\mvc\controllers\HelloWorld;
use mvc_framework\core\router\Router;

Router::route('/toto/@id/@nom/@prenom/@step1', function($templating, $http_argv) {
	return $templating->view()->make('index', array_merge([
		'content_type' => 'application/json',
		'title' => 'HelloWorld',
	], $http_argv));
});

Router::route_controller('/toto/@id/@nom/@prenom', HelloWorld::class, 'Get');