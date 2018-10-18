<?php

use mvc_framework\core\install\Install;

require_once __DIR__."/../autoload.php";

$install = new Install();

$install->clone_repos();
$install->genere_all();
$install->install_all();