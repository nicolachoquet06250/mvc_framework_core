<?php

namespace mvc_framework\core\install;

class Install
{
	protected $generated_files, $repos, $sys_require, $app_infos, $packagers, $modules, $app_dirs;

	public function __construct() {
		$this->generated_files = [
			'package.json'  => 'genere_package_json',
			'composer.json' => 'genere_composer_json',
			'autoload.php'  => 'genere_autoload',
		];

		$this->repos = [
			'orm'    => [
				'repo' => 'https://github.com/nicolachoquet06250/mvc_framework_core_orm.git',
				'path' => 'core/orm'
			],
			'router' => [
				'repo' => 'https://github.com/nicolachoquet06250/mvc_framework_core_router.git',
				'path' => 'core/router'
			],
		];

		$this->sys_require = [
			'git',
			'composer',
			'nodejs',
			'node',
			'npm',
		];

		$this->app_infos = file_exists(__DIR__.'/../../conf/app_infos.json') ?
			json_decode(file_get_contents(__DIR__.'/../../conf/app_infos.json'), true) : [
				'app_name' => 'mvc_framework'
			];

		$this->packagers = [
			'npm'      => 'npm_install',
			'composer' => 'composer_install',
		];

		$this->modules = [
			'vendor',
			'install',
			'logger',
			'orm',
			'router',
		];

		$this->app_dirs = [
			'app/private',
			'app/public/css',
			'app/public/scss',
			'app/public/js',
			'app/public/images',
		];
	}

	public function genere_all() {
		$this->genere_base_conf();
		$this->genere_app_dirs();
		foreach ($this->generated_files as $generated_file => $function) {
			var_dump(__DIR__.'../../'.$generated_file);
			file_put_contents(__DIR__.'../../'.$generated_file, $this->$function());
		}

		return $this;
	}

	protected function genere_package_json() {
		return json_encode(
			[
				'name'         => $this->app_infos['app_name'],
				'version'      => '1.0.0',
				'dependencies' => [
					'node-sass' => '^4.9.4'
				]
			]
		);
	}

	protected function genere_composer_json() {
		return json_encode(
			[
				'name'    => $this->app_infos['app_name'],
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
		);
	}

	protected function genere_autoload() {
		$autoload = '<?php
	';
		foreach ($this->modules as $module) {
			$autoload .= 'require "'.$module.'/autoload.php";
	';
		}
		return $autoload;
	}

	protected function genere_base_conf() {
		$conf_path = __DIR__.'/../../conf';
		if (!is_dir($conf_path)) {
			mkdir($conf_path);
		}
		$conf = [
			"app_name" => "mvc_framework",
			"scripts"  => [
				"install" => "php core/scripts/install.php",
				"update"  => "php core/scripts/update.php",
			],
		];

		file_put_contents($conf_path.'/app_infos.json', json_encode($conf));
	}

	protected function genere_app_dirs() {
		foreach ($this->app_dirs as $app_dir) {
			if(!is_dir(__DIR__.'/../../'.$app_dir)) {
				mkdir(__DIR__.'/../../'.$app_dir, 0777, true);
			}
		}
	}

	public function clone_repos() {
		$this->install_sys_require();
		foreach ($this->repos as $repo_name => $repo) {
			$this->system('git clone '.$repo['repo'].' ../../'.$repo['path']);
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

	public function install_all() {
		foreach ($this->packagers as $packager => $function) {
			$this->$function();
		}
	}

	protected function install_sys_require() {
		foreach ($this->sys_require as $require) {
			if (!$this->sys_require_exists($require)) {
				$this->system('sudo apt install '.$require);
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
}