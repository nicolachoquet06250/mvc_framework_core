<?php

namespace mvc_framework\core\mvc;


class Model {
	private $http_argv, $templating, $api_argv;

	public function __construct($templating, $http_argv, $api_argv) {
		$this->templating = $templating;
		$this->api_argv = $api_argv;
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

	public function get_api_vars($key = null) {
		if($key) {
			if(isset($this->api_argv[$key])) {
				return $this->api_argv[$key];
			}
			else return null;
		}
		return $this->api_argv;
	}
}