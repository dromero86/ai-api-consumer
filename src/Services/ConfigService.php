<?php

namespace Tero\Services;

class ConfigService { 

    private string $file = "config/services.json";

    private $jsonObject = NULL;

    public function __construct(){

        $path = $_ENV["BASEPATH"]."/{$this->file}";

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