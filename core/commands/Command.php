<?php

namespace mvc_framework\core\commands;

require_once __DIR__.'/../install/autoload.php';

class Command {
	private $argv, $app_infos, $install;

	public function __construct($argv) {
		$this->argv = $argv;
		$this->app_infos = file_exists(__DIR__.'/../../conf/app_infos.json') ?
			json_decode(file_get_contents(__DIR__.'/../../conf/app_infos.json'), true) : [
				'app_name' => 'mvc_framework',
			];
		$this->install = new \mvc_framework\core\install\Install();
	}

	public function get_app_infos() {
		return $this->app_infos;
	}

	public function get_argv() {
		return $this->argv;
	}

	public function execute() {
		if(isset($this->get_app_infos()['scripts'])) {
			if(isset($this->get_app_infos()['scripts'][$this->get_argv()[0]])) {
				$cmd = $this->get_app_infos()['scripts'][$this->get_argv()[0]];
				$this->install->system($cmd);
			}
			else {
				$cmd = realpath(__DIR__.'/../scripts/'.$this->get_argv()[0].'.php');
				if(file_exists(realpath(__DIR__.'/../scripts/'.$this->get_argv()[0].'.php'))) {
					$this->install->include_php_file($cmd);
				}
			}
		}
		else {
			$cmd = realpath(__DIR__.'/../scripts/'.$this->get_argv()[0].'.php');
			if(file_exists(realpath(__DIR__.'/../scripts/'.$this->get_argv()[0].'.php'))) {
				$this->install->include_php_file($cmd);
			}
		}
	}

	public static function clean_argv(&$argv) {
		unset($argv[0]);
		$tmp = [];
		foreach ($argv as $arg) {
			if($arg !== null) {
				$tmp[] = $arg;
			}
		}
		$argv = $tmp;
	}

}