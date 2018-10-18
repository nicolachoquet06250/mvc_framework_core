<?php

use mvc_framework\app\mvc\controllers\Errors;
use Philo\Blade\Blade;

require_once 'core/autoload.php';

try {
	$starter = new \mvc_framework\core\starter\AppStarter(\mvc_framework\core\starter\AppStarter::HTTP_VARS_CLEANED());
	echo $starter->execute();
}
catch (Exception $e) {
	$views = realpath(__DIR__.'/../../app/private');
	$cache = realpath(__DIR__.'/../../app/public/mvc/blade_cache');

	require_once __DIR__.'/app/public/mvc/controllers/Errors.php';
	$controller = new Errors(new Blade($views, $cache), \mvc_framework\core\starter\AppStarter::HTTP_VARS_CLEANED());
	return $controller->_404($e->getMessage());
}