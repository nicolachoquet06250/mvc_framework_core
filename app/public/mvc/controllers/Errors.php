<?php

namespace mvc_framework\app\mvc\controllers;

use mvc_framework\core\mvc\Controller;


class Errors extends Controller {
	public function _404($message = '') {
		header('HTTP/1.0 404 NOT FOUND');
		return $this->get_template('errors.404', [
			'argv' => $this->get_argv(),
			'message' => $message,
		])->render();
	}
}