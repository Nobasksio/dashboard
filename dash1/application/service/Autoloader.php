<?php

namespace application\service;

class Autoloader {

    public static function register()
    {
        spl_autoload_register([__CLASS__, 'autoload'], true);
    }

    /**
     * Handles autoloading of classes.
     *
     * @param string $class a class name
     */
    public static function autoload($class)
    {
		$filename = BASE_PATH . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $class) . '.php';
		
		if (is_file($filename)) {
			include($filename);
			return true;
		}
		
		return false;
    }
}