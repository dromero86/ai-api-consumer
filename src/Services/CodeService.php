<?php

namespace Tero\Services;

use Tero\Services\ConfigService;

class CodeService {

    const PHP_START_TAG = '<?php';
    const PHP_END_TAG = '?>';

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

        if( stripos($text, self::PHP_END_TAG) > -1 )
            $text = str_replace(self::PHP_END_TAG,"", $text);

        if( stripos($text, self::PHP_START_TAG) === false )
            $text = self::PHP_START_TAG."\n".$text; 

        return $text;
    }
}