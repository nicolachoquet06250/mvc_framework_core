<?php

namespace mvc_framework\core\mvc;


class Controller {
	private $templating, $http_argv, $api_argv, $model, $model_class;

	public function __construct($templating, $http_argv = [], $api_argv = []) {
		$this->templating = $templating;
		$this->http_argv = $http_argv;
		$this->api_argv = $api_argv;
		if(file_exists(__DIR__.'/../../app/public/mvc/models/'.explode('\\', __CLASS__)[count(explode('\\', __CLASS__))-1])) {
			$this->model_class = str_replace('controllers', 'models', __CLASS__);
			$this->model = new $this->model_class($templating, $http_argv);
		}
		else {
			$this->model_class = null;
			$this->model = null;
		}
	}

	/**
	 * @param null $key
	 * @return string|array|null
	 */
	protected function get_argv($key = null) {
		if($key) {
			if(isset($this->http_argv['VARS'][$key])) {
				return $this->http_argv['VARS'][$key];
			}
			else return null;
		}
		return $this->http_argv;
	}

	protected function get_template($template, $vars = []) {
		return $this->get_templating()->view()->make($template, $vars);
	}

	protected function get_templating() {
		return $this->templating;
	}

	protected function get_model($class = false) {
		return $this->{$class ? 'model_class' : 'model'};
	}

	protected function get_api_var($key = null) {
		if($key) {
			if(isset($this->api_argv[$key])) {
				return $this->api_argv[$key];
			}
			else return null;
		}
		return $this->api_argv;
	}
}