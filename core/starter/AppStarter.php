<?php

namespace mvc_framework\core\starter;

use mvc_framework\app\mvc\controllers\Errors;
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
					require_once __DIR__.'/../../app/public/mvc/routage/'.$file;
				}
			}
			return Router::execute_route($_SERVER['REQUEST_URI'], $this->blade, $this->argv);
		}
		else {
			$uri_base = explode('?', $_SERVER['REQUEST_URI'])[0];
			$uri_base = explode('/', $uri_base);
			$ctrl = $uri_base[1];
			$method = ucfirst(strtolower($_SERVER['REQUEST_METHOD']));
			if(file_exists(realpath(__DIR__.'/../../app/public/mvc/controllers/'.$ctrl.'.php'))) {
				require_once realpath(__DIR__.'/../../app/public/mvc/controllers/'.$ctrl.'.php');
				$ctrl_class = '\mvc_framework\app\mvc\controllers\\'.$ctrl;
				if(in_array($method, get_class_methods($ctrl_class))) {
					return (new $ctrl_class($this->blade, $this->argv))->$method();
				}
				return self::_404($this->blade, $this->argv);
			}
			return self::_404($this->blade, $this->argv);
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

	private static function _404($templating, $http_argv) {
		require_once __DIR__.'/../../app/public/mvc/controllers/Errors.php';
		$controller = new Errors($templating, $http_argv);
		return $controller->_404();
	}
}