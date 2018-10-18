<?php

namespace mvc_framework\app\mvc\controllers;

use mvc_framework\core\mvc\Controller;


class HelloWorld extends Controller {
	public function Get() {
		return $this->get_template('accueil')->render();
	}

	public function Post() {
		return $this->get_template('accueil-api', [
			'toto' => $_POST['toto'],
		])->render();
	}

	public function Delete() {

	}

	public function Put() {

	}
}