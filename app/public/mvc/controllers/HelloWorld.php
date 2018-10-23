<?php

namespace mvc_framework\app\mvc\controllers;

use mvc_framework\core\mvc\Controller;

/**
 * @class
 *
 * @author Nicolas Choquet
 * @date 2018-10-22
 *
 * @title Le controlleur HelloWorld
 *
 * @description
 * Voici la description du controlleur HelloWorld.

 * @code-demo type:url
 * /HelloWorld
 */
class HelloWorld extends Controller {

	/**
	 * @method
	 *
	 * @author Nicolas Choquet
	 * @date 2018-10-22
	 *
	 * @title HelloWorld::Get()
	 *
	 * @description
	 * description de la méthod HelloWorld::Get()
	 *
	 * @code-demo type:url
	 * /HelloWorld HTTP/1.0 GET
	 */
	public function Get() {
		return $this->get_template('accueil', $this->get_api_var())->render();
	}

	/**
	 * @method
	 *
	 * @author Nicolas Choquet
	 * @date 2018-10-22
	 *
	 * @title HelloWorld::Post()
	 *
	 * @description
	 * description de la méthod HelloWorld::Post()
	 *
	 * @code-demo type:url
	 * /HelloWorld HTTP/1.0 POST
	 */
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