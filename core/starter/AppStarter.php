<?php

namespace mvc_framework\core\starter;

use mvc_framework\core\router\Router;
use Philo\Blade\Blade;

class AppStarter {
	private $argv, $blade;

	public function __construct($argv) {
		$views = realpath(__DIR__.'/../../app/private');
		$cache = realpath(__DIR__.'/../../app/public/mvc/blade_cache');

		$this->argv = $argv;
		$this->blade = new Blade($views, $cache);
	}

	public function execute() {
		if(class_exists('\mvc_framework\core\router\Router')) {
			$dir = opendir(__DIR__.'/../../app/public/mvc/routage');
			while (($file = readdir($dir)) !== false) {
				if($file !== '.' && $file !== '..') {
					require __DIR__.'/../../app/public/mvc/routage/'.$file;
				}
			}
			return Router::execute_route($_SERVER['REQUEST_URI'], $this->blade, $this->argv);
		}
		else {
			var_dump($_SERVER['REQUEST_URI']);
		}
	}

	public static function HTTP_VARS_CLEANED() {
		if($_SERVER['REQUEST_METHOD'] !== 'GET') {
			return [
				'VARS' => $_POST,
				'METHOD' => $_SERVER['REQUEST_METHOD']
			];
		}
		return [
			'VARS' => $_GET,
			'METHOD' => $_SERVER['REQUEST_METHOD']
		];
	}
}