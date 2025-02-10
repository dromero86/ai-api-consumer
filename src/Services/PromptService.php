<?php

namespace Tero\Services;

use stdClass;
use Tero\Services\ConfigService;

class PromptService {

    const EXT = 'php';
    const NS = '{main}\\\\{module}';
    const PATH = 'src/{module}/{classname}.{ext}';

    private string $_prompt     = "";
    private string $_resource   = "";
    private string $_language   = "";
    private string $_restriction= "";

    private $_resourceObject = NULL;
    private $configService;

    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
    }

    function load(string $promptText, string $resourceText, string $languageText, string $restrictionText ){
        $this->_prompt          = $promptText;
        $this->_resource        = $resourceText;
        $this->_language        = $languageText;
        $this->_restriction     = $restrictionText;
        $this->_resourceObject  = NULL;
    }

	private function _replace($str, $arr) { 
		foreach ($arr as $k => $v) 
		{
			$str = str_replace('{'.$k.'}', $v, $str);
		} 
		return $str;
	}

    public function resourceDecode(){

        $inyector = $this->configService->get('inyector'); 

        $item = explode("/", $this->_resource);

        $resource               = new stdClass;
        $resource->main         = $inyector->namespace;
        $resource->module       = $item[0];
        $resource->classname    = $item[1];
        $resource->ext          = self::EXT;
        $resource->namespace    = $this->_replace(self::NS  , ["main"=> $resource->main, "module"=> $resource->module ]);
        $resource->path         = $this->_replace(self::PATH, ["main"=> $resource->main, "module"=> $resource->module, "ext"=> $resource->ext ]);

        $this->_resourceObject = $resource;
    }

    public function replaceResource(){
        $this->resourceDecode();
        return $this->_replace($this->_prompt, (array)$this->_resourceObject);
    }

    public function render(){

        $prompt = $this->configService->get('prompt'); 

        $data   = [];
        $data []= $this->configService->replace($prompt->request    , ["user_request" => $this->replaceResource()       ]);
        $data []= $this->configService->replace($prompt->namespace  , ["namespace"    => $this->getNamespace()          ]); 
        $data []= $this->configService->replace($prompt->language   , ["lang"         => strtoupper($this->_language)   ]);
        $data []= $this->configService->replace($prompt->restriction, ["restriction"  => $this->_restriction            ]); 

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