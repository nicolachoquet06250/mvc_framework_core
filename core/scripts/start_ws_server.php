<?php

require_once __DIR__.'/../autoload.php';

$server = new \mvc_framework\core\starter\ServerStarter('localhost', 2108);
$server->route('/chat', new Chat, ['*']);
$server->run();

if(!$server->is_actived()) {
	echo 'Le module websocket n\'est pas installé, Veuillez l\'installer !'."\n";
}