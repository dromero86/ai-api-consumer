<?php

namespace Tero\Services;

use Tero\Services\ConfigService;

class CodeService {

    private $scope;

    public function __construct(ConfigService $configService)
    {   
        $this->scope = $configService->get('code');
    }

    public function distill(string $text){

        if( stripos($text, $this->scope->search) < 0 ) return $text;

        preg_match($this->scope->wrapper, $text, $matches);

        if(!isset($matches[1]))return $text;

        $text = trim($matches[1]);

        $text = str_replace("?>","", $text);

        return $text;
    }
}