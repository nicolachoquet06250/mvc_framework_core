<?php

use mvc_framework\core\install\Install;

require_once __DIR__."/../install/Install.php";

$install = new Install();
$install->genere_htaccess();