<?php

use \mvc_framework\core\starter\AppStarter;
use \mvc_framework\core\paths\Path;
use \Philo\Blade\Blade;

require_once 'core/autoload.php';

try {
	echo (new AppStarter(
		AppStarter::HTTP_VARS_CLEANED()
	))->execute();
}
catch (Exception $e) {
	echo AppStarter::_404(
		new Blade(
			Path::get('blade_source'),
			Path::get('blade_cache')
		),
		AppStarter::HTTP_VARS_CLEANED(),
		$e->getMessage());
}