<?php

namespace mvc_framework\core\install;

use mvc_framework\core\router\Htaccess;

class Install {
	protected $generated_files, $repos,
		$sys_require, $app_infos,
		$packagers, $modules,
		$app_dirs;

	public function __construct() {
		$this->generated_files = json_decode(file_get_contents(__DIR__.'/../conf/generated_files.json'), true);
		$this->repos = json_decode(file_get_contents(__DIR__.'/../conf/external_repositorys.json'), true);
		foreach ($this->repos as $name => $repo) {
			$this->repos[$name]['path'] = str_replace('{__DIR__}', __DIR__, $repo['path']);
		}
		$this->sys_require = json_decode(file_get_contents(__DIR__.'/../conf/dependencies.json'), true);
		$this->app_infos = file_exists(__DIR__.'/../../conf/app_infos.json') ?
			json_decode(file_get_contents(__DIR__.'/../../conf/app_infos.json'), true)
			: json_decode(file_get_contents(__DIR__.'/../conf/default_app_infos.json'), true);
		$this->packagers = json_decode(file_get_contents(__DIR__.'/../conf/packagers.json'), true);
		$this->modules = json_decode(file_get_contents(__DIR__.'/../conf/modules.json'), true);
		$this->app_dirs = json_decode(file_get_contents(__DIR__.'/../conf/generated_app_dirs.json'), true);
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
			$autoload .= 'require_once "'.$module.'/autoload.php";
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
			if(!is_dir(__DIR__.'/../../'.$app_dir)) {
				if(strstr($app_dir, 'routage') && in_array('router', $this->app_dirs)) {
					mkdir(__DIR__.'/../../'.$app_dir, 0777, true);
				}
			}
		}
	}

	public function genere_htaccess() {
		if(is_dir(__DIR__.'/../router')) {
			require_once __DIR__.'/../router/autoload.php';
			$htaccess_json = json_decode(file_get_contents(realpath(__DIR__.'/../../conf/alternative_router.json')), true);
			$app_infos     = json_decode(file_get_contents(realpath(__DIR__.'/../../conf/app_infos.json')), true);
			$host          = $app_infos['host'];
			$port          = $app_infos['port'];
			Htaccess::init();
			foreach ($htaccess_json as $rewrite) {
				$method      = explode(': ', $rewrite)[0];
				$content     = explode(': ', $rewrite)[1];
				$pattern     = explode(' => ', $content)[0];
				$destination = explode(' => ', $content)[1];
				$destination = str_replace(
					[
						'{host}',
						'{port}'
					], [
						$host,
						$port
					], $destination);
				Htaccess::$method($pattern, $destination);
			}
			Htaccess::genere();

			echo json_encode($htaccess_json);
		}
		else echo "cette fonctionnalitée est actuellement indisponible";
	}

	public function clone_repos() {
		$this->install_sys_require();
		foreach ($this->repos as $repo_name => $repo) {
			$this->system('git clone '.$repo['repo'].' '.$repo['path']);
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
				$this->system('sudo apt-get install '.$require);
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