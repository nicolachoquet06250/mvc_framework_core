<?php

require_once 'core/autoload.php';

$starter = new \mvc_framework\core\starter\AppStarter(\mvc_framework\core\starter\AppStarter::HTTP_VARS_CLEANED());
echo $starter->execute();