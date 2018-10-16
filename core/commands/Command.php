<?php

namespace mvc_framework\core\commands;

class Command {
	private $argv, $app_infos;

	public function __construct($argv) {
		$this->argv = $argv;
		$this->app_infos = json_decode(file_get_contents(__DIR__.'/../../conf/app_infos.json'), true);
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
				require __DIR__.'/../install/autoload.php';

				$cmd = $this->get_app_infos()['scripts'][$this->get_argv()[0]];
				list($out) = (new \mvc_framework\core\install\Install())->system($cmd);
			}
			else {
				var_dump(__DIR__.'/../scripts/'.$this->get_argv()[0].'.php');
				if(file_exists(__DIR__.'/../scripts/'.$this->get_argv()[0].'.php')) {
					var_dump('TOTO');
				}
			}
		}
		else {
			var_dump(__DIR__.'/../scripts/'.$this->get_argv()[0].'.php');
			if(file_exists(__DIR__.'/../scripts/'.$this->get_argv()[0].'.php')) {
				var_dump('TOTO');
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