<?php

	use mvc_framework\core\install\Install;

	require __DIR__."/../install/Install.php";

	$install = new Install();

//	$install->clone_repos();
	$install->genere_all();
	$install->install_all();