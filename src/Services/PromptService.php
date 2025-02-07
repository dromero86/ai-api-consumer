<?php

namespace Tero\Services;

use stdClass;

class PromptService {

    const EXT = 'php';
    const NS = '{main}\\\\{module}';
    const PATH = 'src/{module}/{classname}.{ext}';

    private string $_prompt     = "";
    private string $_resource   = "";
    private string $_language   = "";
    private string $_restriction= "";

    private $_resourceObject = NULL;

    function load(string $promptText, string $resourceText, string $languageText, string $restrictionText )
    {
        $this->_prompt          = $promptText;
        $this->_resource        = $resourceText;
        $this->_language        = $languageText;
        $this->_restriction     = $restrictionText;
        $this->_resourceObject  = NULL;
    }

	private function _replace($str, $arr) 
	{ 
		foreach ($arr as $k => $v) 
		{
			$str = str_replace('{'.$k.'}', $v, $str);
		} 
		return $str;
	}

    public function resourceDecode()
    {
        $item = explode("/", $this->_resource);

        $resource               = new stdClass;
        $resource->main         = "Tero";
        $resource->module       = $item[0];
        $resource->classname    = $item[1];
        $resource->ext          = self::EXT;
        $resource->namespace    = $this->_replace(self::NS, ["main"=> $resource->main, "module"=> $resource->module ]);
        $resource->path         = $this->_replace(self::PATH, ["main"=> $resource->main, "module"=> $resource->module, "ext"=> $resource->ext ]);

        $this->_resourceObject = $resource;
    }

    public function replaceResource()
    {
        $this->resourceDecode();
        return $this->_replace($this->_prompt, (array)$this->_resourceObject);
    }

    public function render()
    {
        $data   = [];
        $data []= $this->replaceResource();
        $data []= "Debe estar en el namespace {$this->getNamespace()}";
        $data []= "Lenguaje: ".strtoupper($this->_language);
        $data []= "{$this->_restriction}"; 

        return implode(". ", $data);
    }

    public function getPath(){
        if($this->_resourceObject)
            return $this->_replace($this->_resourceObject->path, (array)$this->_resourceObject); 
    }

    public function getNamespace(){
        if($this->_resourceObject)
            return $this->_resourceObject->namespace;
    }
}