<?php

namespace Tero\Services;

class CodeService {

    const LANG = 'php';

    public static function Distill(string $text){

        if( stripos($text, "```".self::LANG) > -1 )
        {
            $pattern = "/```".self::LANG."(.*?)```/s";
            $input = $text;
            preg_match($pattern, $input, $matches);

            if(isset($matches[1]))
                return trim($matches[1]);
            else 
                return $text;
        }
        else 
        {
            return $text;
        }
    }
}