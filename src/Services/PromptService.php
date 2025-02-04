<?php

namespace Tero\Services;

use stdClass;

class PromptService {

    const EXT = 'php';
    const NS = '{main}\\{module}';
    const PATH = 'src/{module}/{classname}.{ext}';

	private function _replace($str, $arr) 
	{ 
		foreach ($arr as $k => $v) 
		{
			$str = str_replace('{'.$k.'}', $v, $str);
		} 
		return $str;
	}

    public function resourceDecode(string $value){

        $item = explode("/", $value);

        $resource = new stdClass;
        $resource->main = "Tero";
        $resource->module =  $item[0];
        $resource->classname = $item[1];
        $resource->ext = self::EXT;
        $resource->namespace = $this->_replace(self::NS, ["main"=> $resource->main, "module"=> $resource->module ]);
        $resource->path = $this->_replace(self::PATH, ["main"=> $resource->main, "module"=> $resource->module, "ext"=> $resource->ext ]);

        return $resource;
    }

    public function replaceResource($text, $resource){

        return $this->_replace($text, [
            "main"=> $resource->main, 
            "module"=> $resource->module, 
            "ext"=> $resource->ext 
        ]);
    }
}