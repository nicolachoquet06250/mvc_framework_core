<?php

namespace mvc_framework\core\mvc;


class Controller {
	protected $templating, $http_argv;
	public function __construct($templating, $http_argv = []) {
		$this->templating = $templating;
		$this->http_argv = $http_argv;
	}
}