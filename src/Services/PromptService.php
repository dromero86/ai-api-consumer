<?php

namespace Dromero86\ApiAiConsumer\Services;

use RuntimeException;
use stdClass;
use Dromero86\ApiAiConsumer\Services\ConfigService;

class PromptService {

    private string $EXT = '';
    private string $NS = '';
    private string $PATH = '';
    private string $psr4 = '';

    private string $_prompt     = "";
    private string $_resource   = "";
    private string $_language   = "";
    private string $_restriction= "";

    private $_resourceObject = NULL;
    private $configService;

    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;

        $code = $this->configService->get('code');

        $this->PATH = $code->location; 
        $this->NS   = $code->namespace;
        $this->EXT  = $code->extension;
        $this->psr4 = $code->psr4;
    }

    function load(string $promptText, string $resourceText, string $languageText, string $restrictionText ){
        $this->_prompt          = $promptText;
        $this->_resource        = $resourceText;
        $this->_language        = $languageText;
        $this->_restriction     = $restrictionText;
        $this->_resourceObject  = NULL;
    }

    public function resourceDecode(){


        $item = explode("/", $this->_resource);

        if(!isset($item[1])) throw new RuntimeException("The resource {$this->_resource} havent module");

        $resource               = new stdClass;
        $resource->main         = $this->psr4;
        $resource->module       = $item[0];
        $resource->classname    = $item[1];
        $resource->ext          = $this->EXT;
        $resource->namespace    = $this->configService->replace($this->NS  , ["main"=> $resource->main, "module"=> $resource->module ]);
        $resource->path         = $this->configService->replace($this->PATH, ["main"=> $resource->main, "module"=> $resource->module, "ext"=> $resource->ext ]);

        $this->_resourceObject = $resource;
    }

    public function replaceResource(){
        $this->resourceDecode();
        return $this->configService->replace($this->_prompt, (array)$this->_resourceObject);
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
            return $this->configService->replace($this->_resourceObject->path, (array)$this->_resourceObject); 
    }

    public function getNamespace(){
        if($this->_resourceObject)
            return $this->_resourceObject->namespace;
    }
}