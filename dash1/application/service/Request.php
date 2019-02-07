<?php

namespace application\service;

class Request {
    public static
    	$data=array(),
        $postData=array(),
        $getData=array();  

	public function __construct()
	{
		foreach($_REQUEST as $key=>$value)
		{
			$this->set($key, $value);
		}

        foreach($_POST as $key=>$value)
        {
            $this->setPost($key, $value);
        }   

        foreach($_GET as $key=>$value)
        {
            $this->setGet($key, $value);
        }               
	}

    public function set($key, $value)
    {
    	self::$data[$key] = $value;
    }

    public function setPost($key, $value)
    {
        self::$postData[$key] = $value;
    } 

    public function setGet($key, $value)
    {
        self::$getData[$key] = $value;
    }        

    public function get($key)
    {
    	return isset(self::$data[$key]) ? self::$data[$key] : null;
    }    

    public function getGet($key)
    {
        return isset(self::$getData[$key]) ? self::$getData[$key] : null;
    }     

    public function getPost($key)
    {
        return isset(self::$postData[$key]) ? self::$postData[$key] : null;
    }    

    public function isPost()
    {
        return !empty(self::$postData);
	}   
	
	public function redirect($url) {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$url);
		exit();
	}
}