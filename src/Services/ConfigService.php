<?php

namespace Tero\Services;

use RuntimeException;

class ConfigService { 

    private string $file = "config/services.json";

    private $jsonObject = NULL;

    public function __construct(){

        $path = $_ENV["BASEPATH"]."/{$this->file}";

        if( !file_exists($path) ) throw new RuntimeException("Config file dont exists {$path}");

        $data = file_get_contents($path);

        $this->jsonObject = json_decode($data);

        $jsonState = json_last_error();

        switch( $jsonState ){
            case JSON_ERROR_NONE: break;
            case JSON_ERROR_UTF8:
            case JSON_ERROR_UTF16:
                throw new RuntimeException("Malformed UTF-[8|16] characters, possibly incorrectly encoded");
                break;
            default: 
                throw new RuntimeException("Invalid or malformed JSON");
                break;
        }

        $this->validarJson();
    }

    function validarJson() {
        $inputItems = [
            'inyector' => ['namespace', 'path'],
            'host' => ['local', 'remote'],
            'api' => ['models', 'chat_completions', 'completions', 'embeddings'],
            'console' => ['message', 'args'],
            'prompt' => ['request', 'namespace', 'language', 'restriction'],
            'code' => ['search', 'wrapper', 'location', 'namespace', 'extension', 'store'],
            'log' => ['enable', 'file', 'template']
        ];
    
        $data = json_decode( json_encode($this->jsonObject), true );

        foreach ($inputItems as $item => $value) {
            if (!isset($data[$item])) {
                throw new RuntimeException("JSON element {$item} not found");
            }
            if (is_array($value)) {
                foreach ($value as $subitem) {
                    if (!isset($data[$item][$subitem])) {
                        throw new RuntimeException("JSON element {$item}.{$subitem} not found");
                    }
                }
            }
        }
    
        return true;
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