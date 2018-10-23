<?php

use mvc_framework\app\mvc\controllers\HelloWorld;
use mvc_framework\core\router\Router;

/**
 * @route
 *
 * @author Nicolas Choquet
 * @date 2018-10-23
 *
 * @title Routes pour le controlleur HelloWorld.
 *
 * @description
 * Routes pour le controlleur HelloWorld
 *
 * @code-demo type:url
 * /toto/$id/$nom/$prenom/$step1
 * /toto/$id/$nom/$prenom
 */
function HelloWorld_routes() {
	Router::route('/toto/@id/@nom/@prenom/@step1', function ($templating, $http_argv, $api_argv) {
		return $templating->view()->make('index', array_merge([
																  'content_type' => 'application/json',
																  'title'        => 'HelloWorld',
															  ], [
																  'argv' => $http_argv,
																  'http' => $api_argv,
															  ]));
	}, 'api');

	Router::route_controller('/toto/@id/@nom/@prenom', HelloWorld::class, 'Get', 'front');
}
HelloWorld_routes();