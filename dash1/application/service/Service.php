<?php

namespace application\service;

class Service {
    private static $_registry = null;

    public static function get($index)
    {
        if(isset(self::$_registry[$index]))
        {
            return self::$_registry[$index];
        }
	}
	
	public static function set($index, $value)
    {
        self::$_registry[$index] = $value;
    }	

    public static function config()
    {
        if(isset(self::$_registry["config"]))
        {
            return self::$_registry["config"];
        }
	}
	
    public static function view()
    {
        if(isset(self::$_registry["view"]))
        {
            return self::$_registry["view"];
        }
	}
	
    public static function request()
    {
        if(isset(self::$_registry["request"]))
        {
            return self::$_registry["request"];
        }
	}	

	public static function session()
    {
        if(isset(self::$_registry["session"]))
        {
            return self::$_registry["session"];
        }
	}
	
	public static function cookie()
    {
        if(isset(self::$_registry["cookie"]))
        {
            return self::$_registry["cookie"];
        }
	}
}