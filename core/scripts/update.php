<?php

use mvc_framework\core\install\Install;

require __DIR__."/../autoload.php";

$install = new Install();

$install->genere_all();
$install->install_all();