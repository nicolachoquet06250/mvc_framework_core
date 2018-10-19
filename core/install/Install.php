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

	public function genere_css_doc() {
		$ctrl_content = '<?php

	namespace mvc_framework\app\mvc\controllers;
	
	use mvc_framework\core\mvc\Controller;
	
	class Documentation extends Controller {
		public function Get() {
			if($this->get_argv(\'t\') === \'css\') {
				return $this->get_template(\'documentation.css\', [\'doc_json\' => json_encode((new \mvc_framework\core\doc_parser\Parser(\'scss\'))->parse())])->render();
			}
			return \mvc_framework\core\starter\AppStarter::_404(
				$this->get_templating(), 
				$this->get_argv(), 
				\'La documentation n\\\'est pas encore disponible pour la partie \'.$this->get_argv(\'t\')
			);
		}
	}
';

		$view_content = '@extends(\'common.layout-front\', [
	\'title\' => \'Documentation Css\'
])

@section(\'before_body_css\')
	<link rel="stylesheet" href="/css/concat/main" />
	<link rel="stylesheet" href="/css/prism" />
@endsection

@section(\'body_content\')
	<nav class="fixed-top bg-white">
		<ul class="nav nav-tabs">
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
					<i class="fa fa-bars"></i>
				</a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="?t=css">css</a>
					<a class="dropdown-item" href="?t=php">php</a>
				</div>
			</li>
			<li class="nav-item">
				<a href="#" class="nav-link disabled">
					Documentation CSS
				</a>
			</li>
		</ul>
	</nav>
	{{ $documentation = json_decode($doc_json, true) }}
	<?php $i = 0; ?>
	<div class="container">
		@foreach($documentation as $id => $doc)
			<div class="row">
				@if(count($doc) > 1)
					@component(\'components.doc_block\', [
						\'doc_part\' => [
							\'file\' => $id,
							\'author\' => isset($doc[\'author\']) ? $doc[\'author\'] : \'\',
							\'date\' => isset($doc[\'date\']) ? $doc[\'date\'] : \'\',
							\'title\' => $doc[\'title\'],
							\'description\' => $doc[\'description\'],
							\'modifiers\' => $doc[\'modifiers\'],
							\'code_demo\' => isset($doc[\'code-demo\']) ? $doc[\'code-demo\'] : \'\',
						],
						\'col\' => 12,
						\'supplement_class\' => ($i === 0 ? \'mt-5\' : \'\'),
					])@endcomponent
					<?php $i++; ?>
				@endif
			</div>
		@endforeach
	</div>
@endsection

@section(\'after_body_script\')
	<script src="/js/prism"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.js"
			integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
			crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
			integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
			crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"
			integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em"
			crossorigin="anonymous"></script>
	<script>
        $(function () {
            $(\'.dropdown-toggle\').dropdown();
            $(\'a[data-toggle="tab"]\').on(\'shown.bs.tab\', function (e) {
                e.target // newly activated tab
                e.relatedTarget // previous active tab
            })
        });
	</script>
@endsection
';

		if(!is_dir(__DIR__.'/../../app/private/documentation')) {
			mkdir(__DIR__.'/../../app/private/documentation');
		}

		file_put_contents(__DIR__.'/../../app/private/documentation/css.blade.php', $view_content);
		file_put_contents(__DIR__.'/../../app/public/mvc/controllers/Documentation.php', $ctrl_content);
	}

	public function css_concat() {
		$css_path = Path::get('sass_dest');

		$dir = opendir($css_path);
		$css_files = [];
		$concat_content = '';

		while (($file = readdir($dir)) !== false) {
			if($file !== '.' && $file !== '..' && is_file($css_path.'/'.$file)) {
				$css_files[] = $css_path.'/'.$file;
			}
		}
		asort($css_files);

		foreach ($css_files as $css_file) {
			$concat_content .= str_replace(["\n", '../../../core'], ['', '../../../../core'], file_get_contents($css_file));
		}

		if (Path::get('sass_concat') === '') {
			mkdir(Path::get('sass_dest').'/concat', 0777, true);
		}

		file_put_contents(Path::get('sass_concat').'/main.css', $concat_content);
	}
}