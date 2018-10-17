<?php

namespace mvc_framework\core\starter;

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
		return $this->blade->view()->make('index', [
			'content_type' => 'application/json',
			'argv' => $this->argv
		])->render();
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