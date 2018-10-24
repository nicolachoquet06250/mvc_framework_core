<?php

namespace mvc_framework\core\starter;

class ServerStarter {
	/**
	 * @var AppStarter|\Ratchet\App $app
	 */
	protected $app;
	protected $types = ['http', 'ws'];
	protected $host, $port;
	protected $ws_enabled = false;

	public function __construct($host = 'localhost', $port = null) {
		$this->host = $host;
		$this->port = $port;
		if(is_dir(__DIR__.'/../websocket')) {
			$this->ws_enabled = true;
		}

		if ($this->is_actived()) {
			$this->app = new \Ratchet\App(
				$this->host,
				$this->port
			);
		}
	}

	public function route($path = '/', $controller = null, array $allowed_origins = []) {
		if($this->is_actived()) {
			$this->app->route($path, $controller, $allowed_origins, $this->host);
		}
	}

	public function is_actived() {
		return $this->ws_enabled;
	}

	public function run() {
		if($this->is_actived()) {
			$this->app->run();
		}
	}
}