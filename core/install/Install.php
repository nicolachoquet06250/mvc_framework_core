<?php

namespace mvc_framework\core\install;

use mvc_framework\core\paths\Path;

class Install {
	protected $generated_files, $repos,
		$sys_require, $app_name,
		$packagers, $modules,
		$app_dirs, $npm_global_dependencies;

	public function __construct() {
		$this->generated_files = Path::get_file('core_generated-files');
		$this->repos = Path::get_file('core_external-repositorys');
		$this->sys_require = Path::get_file('core_dependencies');
		$this->app_name = Path::get_file('app-infos') !== '' ? Path::get('app-infos_app-name') : Path::get('default-app-infos_app-name');
		$this->packagers = Path::get_file('core_packagers');
		$this->modules = Path::get_file('core_modules');
		$this->app_dirs = Path::get_file('core_generated-app-dirs');
		$this->npm_global_dependencies = Path::get_file('core_npm-global-dependencies');
	}

	public function genere_all($update = false) {
		$this->genere_base_conf();
		$this->genere_app_dirs();
		foreach ($this->generated_files as $generated_file => $function) {
			$this->$function(__DIR__.'../../'.$generated_file, $update);
		}

		return $this;
	}

	protected function genere_package_json($path, $update = false) {
		if(!$update) {
			file_put_contents($path, json_encode(
				[
					'name'         => $this->app_name,
					'version'      => '1.0.0',
					'dependencies' => [
						'node-sass' => '^4.9.4'
					]
				]
			));
		}
	}

	protected function genere_composer_json($path, $update = false) {
		if(!$update) {
			file_put_contents($path, json_encode(
				[
					'name'    => $this->app_name,
					'type'    => 'project',
					'authors' => [
						[
							"name"  => "nicolas.choquet",
							"email" => "nicolas.choquet@lagardere-active.com",
						]
					],
					'require' => [
						'philo/laravel-blade' => '3.*'
					]
				]
			));
		}
	}

	protected function genere_autoload($path) {
		$autoload = '<?php
	';
		foreach ($this->modules as $module) {
			$autoload .= 'require_once "'.$module.'/autoload.php";
	';
		}
		file_put_contents($path, $autoload);
	}

	protected function genere_base_conf() {
		$conf_path = __DIR__.'/../../conf';
		if (!is_dir($conf_path)) {
			mkdir($conf_path);
		}
		$conf = [
			"app_name" => "mvc_framework",
			"host" => "localhost",
			"port" => 2107,
			"scripts"  => [
				"start" => "php -S localhost:2107",
				"install" => "php core/scripts/install.php",
				"update"  => "php core/scripts/update.php",
			],
		];

		file_put_contents($conf_path.'/app_infos.json', json_encode($conf));
	}

	protected function genere_app_dirs() {
		foreach ($this->app_dirs as $app_dir) {
			if(!is_dir($app_dir)) {
				mkdir(__DIR__.'/../../'.$app_dir, 0777, true);
			}
		}
	}

	public function clone_repos() {
		$this->install_sys_require();
		foreach ($this->repos as $repo_name => $repo) {
			if(is_dir($repo['path'])) {
				$this->system('cd '.$repo['path'].'; git pull');
			}
			else {
				$this->system('git clone '.$repo['repo'].' '.$repo['path']);
			}
		}

		return $this;
	}

	private function sys_require_exists($require) {
		$out = [];
		exec('dpkg -l | grep '.$require, $out);
		return count($out) > 0;
	}

	public function system($cmd, $vars = []) {
		$out    = [];
		$return = [];

		foreach ($vars as $id => $var) {
			$cmd = str_replace('$'.$id, $var, $cmd);
		}
		exec($cmd, $out, $return);
		return [
			$out,
			$return,
		];
	}

	public function include_php_file($file_path) {
		include $file_path;
	}

	public function install_all() {
		$this->install_npm_global_dependencies();
		foreach ($this->packagers as $packager => $function) {
			$this->$function();
		}
	}

	protected function install_sys_require() {
		foreach ($this->sys_require as $require) {
			if (!$this->sys_require_exists($require)) {
				$this->system('sudo apt-get install '.$require.' -y');
			}
		}
	}

	protected function npm_install() {
		if (file_exists(__DIR__.'/../package.json')) {
			$this->system('cd core; npm install');
		}
	}

	protected function composer_install() {
		if (file_exists(__DIR__.'/../composer.json')) {
			$this->system('cd core; composer install');
		}
	}

	protected function install_npm_global_dependencies() {
		foreach ($this->npm_global_dependencies as $npm_global_dependency) {
			$this->system('sudo npm install -g '.$npm_global_dependency);
		}
	}

	public function sass_compile() {
		$this->system('node-sass '.Path::get('sass_source').' --output='.Path::get('sass_dest'));
	}
}