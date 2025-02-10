<?php

namespace Tero\Services;

use RuntimeException;

class ConfigService { 

    private string $file = "config/services.json";

    private $jsonObject = NULL;

    public function __construct(){

        $path = $_ENV["BASEPATH"]."/{$this->file}";

        if( !file_exists($path) ) throw new RuntimeException("Config file no exists {$path}");

        $data = file_get_contents($path);

        $this->jsonObject = json_decode($data);
    }

    public function get(string $key){
        return isset($this->jsonObject->{$key}) ? $this->jsonObject->{$key} : FALSE; 
    }

    public function replace($str, $arr) { 
		foreach ($arr as $k => $v) 
		{
			$str = str_replace('{'.$k.'}', $v, $str);
		} 
		return $str;
	}

}