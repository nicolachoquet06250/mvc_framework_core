<?php

use \mvc_framework\core\router\Router;
use \mvc_framework\app\mvc\controllers\HelloWorld;

Router::route_controller('/', HelloWorld::class, 'Get', 'front');