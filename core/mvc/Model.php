<?php

namespace mvc_framework\core\mvc;


class Model {
	private $http_argv, $templating;

	public function __construct($templating, $http_argv) {
		$this->templating = $templating;
		$this->http_argv = $http_argv;
	}

	public function get_template($template, $vars) {
		return $this->templating->view()->make($template, $vars);
	}

	public function get_argv($key = null) {
		if($key) {
			if(isset($this->http_argv[$key])) {
				return $this->http_argv[$key];
			}
			else return null;
		}
		return $this->http_argv;
	}
}