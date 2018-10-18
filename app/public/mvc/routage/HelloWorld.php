<?php

use mvc_framework\app\mvc\controllers\HelloWorld;
use mvc_framework\core\router\Router;

Router::route('/toto/@id/@nom/@prenom/@step1', function($templating, $http_argv, $api_argv) {
	return $templating->view()->make('index', array_merge([
		'content_type' => 'application/json',
		'title' => 'HelloWorld',
	], [
		'argv' => $http_argv,
		'http' => $api_argv,
	]));
});

Router::route_controller('/toto/@id/@nom/@prenom', HelloWorld::class, 'Get');