<?php

namespace mvc_framework\core\paths;


class Path {
	public static function get($identifier) {
		$identifier = explode('_', $identifier);
		foreach ($identifier as $id => $ident) {
			$identifier[$id] = str_replace('-', '_', $ident);
		}

		if(count($identifier) === 2) {
			if(file_exists(__DIR__.'/../../conf/'.$identifier[0].'.json')) {
				$content = json_decode(file_get_contents(__DIR__.'/../../conf/'.$identifier[0].'.json'), true);
				if(isset($content[$identifier[1]])) {
					if(self::is_path($content[$identifier[1]])) {
						$content[$identifier[1]] = self::clean_path($content[$identifier[1]]);
					}
					return $content[$identifier[1]];
				}
			}
		}
		elseif (count($identifier) === 3) {
			if(file_exists(__DIR__.'/../../'.$identifier[0].'/conf/'.$identifier[1].'.json')) {
				$content = json_decode(file_get_contents(__DIR__.'/../../'.$identifier[0].'/conf/'.$identifier[1].'.json'), true);
				if(isset($content[$identifier[2]])) {
					if(self::is_path($content[$identifier[2]])) {
						$content[$identifier[2]] = self::clean_path($content[$identifier[2]]);
					}
					return $content[$identifier[2]];
				}
			}
		}
		elseif (count($identifier) === 4) {
			$prop = $identifier[count($identifier)-1];
			$file = $identifier[count($identifier)-2].'.json';
			$base_path = $identifier[0].'/'.$identifier[1].'/conf';

			$complete_path = realpath(__DIR__.'/../../'.$base_path.'/'.$file);

			if(file_exists($complete_path)) {
				$content = json_decode(file_get_contents($complete_path), true);
				if(isset($content[$prop])) {
					if(self::is_path($content[$prop])) {
						$content[$prop] = self::clean_path($content[$prop]);
					}
					return $content[$prop];
				}
			}
		}
		return '';
	}

	private static function clean_path($path) {
		$path = str_replace('{__DIR__}', __DIR__, $path);
		if(!realpath($path)) {
			return $path;
		}
		return realpath($path);
	}

	private static function is_path($string) {
		return is_string($string) && strstr($string, '/') && !strstr($string, 'http://') && !strstr($string, 'https://');
	}

	public static function get_file($identifier) {
		$identifier = explode('_', $identifier);
		foreach ($identifier as $id => $ident) {
			$identifier[$id] = str_replace('-', '_', $ident);
		}

		if(count($identifier) === 1) {
			if(file_exists(__DIR__.'/../../conf/'.$identifier[0].'.json')) {
				$content = json_decode(file_get_contents(__DIR__.'/../../conf/'.$identifier[0].'.json'), true);
				foreach ($content as $id => $line) {
					if(self::is_path($line)) {
						$content[$id] = self::clean_path($line);
					}
					elseif (is_array($line)) {
						foreach ($line as $_id => $_line) {
							if(self::is_path($_line)) {
								$content[$id][$_id] = self::clean_path($_line);
							}
						}
					}
				}
				return $content;
			}
		}
		elseif (count($identifier) === 2) {
			if(file_exists(__DIR__.'/../../'.$identifier[0].'/conf/'.$identifier[1].'.json')) {
				$content = json_decode(file_get_contents(__DIR__.'/../../'.$identifier[0].'/conf/'.$identifier[1].'.json'), true);
				foreach ($content as $id => $line) {
					if(self::is_path($line)) {
						$content[$id] = self::clean_path($line);
					}
					elseif (is_array($line)) {
						foreach ($line as $_id => $_line) {
							if(self::is_path($_line)) {
								$content[$id][$_id] = self::clean_path($_line);
							}
						}
					}
				}
				return $content;
			}
		}
		elseif (count($identifier) === 3) {
			$file = $identifier[2].'.json';
			$base_path = $identifier[0].'/'.$identifier[1].'/conf';

			$complete_path = realpath(__DIR__.'/../../'.$base_path.'/'.$file);

			if(file_exists($complete_path)) {
				$content = json_decode(file_get_contents($complete_path), true);
				foreach ($content as $id => $line) {
					if(self::is_path($line)) {
						$content[$id] = self::clean_path($line);
					}
					elseif (is_array($line)) {
						foreach ($line as $_id => $_line) {
							if(self::is_path($_line)) {
								$content[$id][$_id] = self::clean_path($_line);
							}
						}
					}
				}
				return $content;
			}
		}
		return '';
	}
}