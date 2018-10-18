<?php

	require_once __DIR__.'/../autoload.php';

	$parser = new \mvc_framework\core\doc_parser\Parser('scss');
	$doc = $parser->parse();

	$ctrl_content = '<?php

	namespace mvc_framework\app\mvc\controllers;
	
	use mvc_framework\core\mvc\Controller;
	
	class Documentation extends Controller {
		public function Get() {
			if($this->get_argv(\'t\') === \'css\') {
				return $this->get_template(\'documentation.css\', [\'doc_json\' => \''.str_replace("'", "\'", json_encode($doc)).'\'])->render();
			}
			return \mvc_framework\core\starter\AppStarter::_404(
				$this->get_templating(), 
				$this->get_argv(), 
				\'La documentation n\\\'est pas encore disponible pour la partie \'.htmlentities($this->get_argv(\'t\'))
			);
		}
	}';

	$view_content = '@extends(\'common.layout-front\', [
	\'title\' => \'Documentation Css\'
])
	
@section(\'body_content\')
	<?php
		var_dump(json_decode($doc_json, true));
	?>
	@component(\'components.doc_block\')
		heyyyyyyyyyyyyyyyyy !!!!!
	@endcomponent
@endsection
	
	';

	if(!is_dir(__DIR__.'/../../app/private/documentation')) {
		mkdir(__DIR__.'/../../app/private/documentation');
	}

	file_put_contents(__DIR__.'/../../app/private/documentation/css.blade.php', $view_content);
	file_put_contents(__DIR__.'/../../app/public/mvc/controllers/Documentation.php', $ctrl_content);