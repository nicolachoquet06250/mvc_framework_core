<?php

namespace mvc_framework\app\mvc\controllers;

use mvc_framework\core\mvc\Controller;


class Errors extends Controller {
	public function _404() {
		header('HTTP/1.0 404 NOT FOUND');
		return $this->templating->view()->make('errors.404', $this->http_argv)->render();
	}
}