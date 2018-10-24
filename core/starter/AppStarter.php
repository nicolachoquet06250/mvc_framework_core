<?php

namespace mvc_framework\core\starter;

use mvc_framework\app\mvc\controllers\Errors;
use mvc_framework\core\paths\Path;
use mvc_framework\core\router\Router;
use Philo\Blade\Blade;
use \Exception;

class AppStarter {
	private $argv, $blade;
	const PAGE_API = 'api';
	const PAGE_FRONT = 'front';

	public function __construct($argv) {
		$this->argv = $argv;
		$this->blade = new Blade(Path::get('blade_source'), Path::get('blade_cache'));
	}

	public function execute() {
		if(strstr($_SERVER['REQUEST_URI'], '/site/css/') || strstr($_SERVER['REQUEST_URI'], '/css/') ||
		   strstr($_SERVER['REQUEST_URI'], '/site/js/') || strstr($_SERVER['REQUEST_URI'], '/js/') ||
		   strstr($_SERVER['REQUEST_URI'], '/site/images/') || strstr($_SERVER['REQUEST_URI'], '/images/')) {
			if(strstr($_SERVER['REQUEST_URI'], '/site/css/') || strstr($_SERVER['REQUEST_URI'], '/css/')) {
				header('Content-Type: text/css');
				$path = Path::get('sass_dest').'/'.str_replace(['/site/css/', '/css/'], '', $_SERVER['REQUEST_URI']).'.css';
				include $path;
			}
			elseif (strstr($_SERVER['REQUEST_URI'], '/site/js/') || strstr($_SERVER['REQUEST_URI'], '/js/')) {
				header('Content-Type: text/javascript');
				$path = Path::get('javascript_source').'/'.str_replace(['/site/js/', '/js/'], '', $_SERVER['REQUEST_URI']).'.js';
				echo file_get_contents($path);
			}
			elseif (strstr($_SERVER['REQUEST_URI'], '/site/images/') || strstr($_SERVER['REQUEST_URI'], '/images/')) {
				$request_uri = str_replace(['/site/images/', '/images/'], '', $_SERVER['REQUEST_URI']);
				$ext = explode('/', $request_uri)[0];
				$request_uri = str_replace($ext.'/', '', $request_uri);
				$path = Path::get('images_source').'/'.$request_uri.'.'.$ext;
				header('Content-Type: '.mime_content_type($path));
				include $path;
			}
 		}
		else {
			if (class_exists('\mvc_framework\core\router\Router')) {
				$dir = opendir(__DIR__.'/../../app/public/mvc/routage');
				while (($file = readdir($dir)) !== false) {
					if ($file !== '.' && $file !== '..') {
						require_once __DIR__.'/../../app/public/mvc/routage/'.$file;
					}
				}
				return Router::execute_route($_SERVER['REQUEST_URI'], $this->blade, $this->argv);
			} else {
				$uri_base = explode('?', $_SERVER['REQUEST_URI'])[0];
				$uri_base = explode('/', $uri_base);
				$ctrl     = $uri_base[1];
				$method   = ucfirst(strtolower($_SERVER['REQUEST_METHOD']));
				if (file_exists(realpath(__DIR__.'/../../app/public/mvc/controllers/'.$ctrl.'.php'))) {
					require_once realpath(__DIR__.'/../../app/public/mvc/controllers/'.$ctrl.'.php');
					$ctrl_class = '\mvc_framework\app\mvc\controllers\\'.$ctrl;
					if (in_array($method, get_class_methods($ctrl_class))) {
						return (new $ctrl_class($this->blade, $this->argv))->$method();
					}
					return self::_404($this->blade, $this->argv, 'Method '.$method.' not found in controller '.$ctrl.'!');
				}
				return self::_404($this->blade, $this->argv, 'Controller '.$ctrl.' not found !');
			}
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

	public static function _404($templating, $http_argv, $message = '', $page_type = AppStarter::PAGE_API) {
		require_once __DIR__.'/../../app/public/mvc/controllers/Errors.php';
		$controller = new Errors($templating, $http_argv);
		return $controller->_404($message, $page_type);
	}

	public static function _500($templating, $http_argv, $message = '', $page_type = AppStarter::PAGE_API) {
		require_once __DIR__.'/../../app/public/mvc/controllers/Errors.php';
		$controller = new Errors($templating, $http_argv);
		return $controller->_500($message, $page_type);
	}
}