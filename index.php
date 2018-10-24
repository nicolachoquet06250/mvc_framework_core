<?php

use \mvc_framework\core\starter\AppStarter;
use \mvc_framework\core\paths\Path;
use \Philo\Blade\Blade;

require_once 'core/autoload.php';
$http_vars_cleaned = AppStarter::HTTP_VARS_CLEANED();
try {
	echo (new AppStarter(
		$http_vars_cleaned
	))->execute();
}
catch (Exception $e) {
	echo AppStarter::_500(
		new Blade(
			Path::get('blade_source'),
			Path::get('blade_cache')
		),
		$http_vars_cleaned,
		$e->getMessage(), AppStarter::PAGE_FRONT);
}