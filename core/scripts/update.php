<?php

use mvc_framework\core\install\Install;

require_once __DIR__."/../Path/autoload.php";
require_once __DIR__."/../autoload.php";

$install = new Install();

$install->clone_repos();
$install->genere_all(true);
$install->install_all();