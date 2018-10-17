<?php

namespace mvc_framework\app\mvc\controllers;

use mvc_framework\core\mvc\Controller;


class HelloWorld extends Controller {
	public function Get() {
		return $this->templating->view()->make('index', [
			'content_type' => 'application/json',
			'title' => 'HelloWorld',
		]);
	}

	public function Post() {

	}

	public function Delete() {

	}

	public function Put() {

	}
}