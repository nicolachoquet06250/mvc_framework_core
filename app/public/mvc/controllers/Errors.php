<?php

namespace mvc_framework\app\mvc\controllers;

use mvc_framework\core\mvc\Controller;
use mvc_framework\core\starter\AppStarter;


class Errors extends Controller {
	public function _404($message = '', $page_type = AppStarter::PAGE_API) {
		header('HTTP/1.0 404 NOT FOUND - '.$message);
		return $this->get_template('errors.404', [
			'argv' => $this->get_argv(),
			'message' => $message,
			'page_type' => $page_type,
		])->render();
	}

	public function _500($message = '', $page_type = AppStarter::PAGE_API) {
		header('HTTP/1.0 500 INTERNAL ERROR - '.$message);
		return $this->get_template('errors.500', [
			'argv' => $this->get_argv(),
			'message' => $message,
			'page_type' => $page_type,
		])->render();
	}
}