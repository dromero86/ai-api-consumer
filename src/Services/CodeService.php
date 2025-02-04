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

            if(isset($matches[1])){
                $text = trim($matches[1]);
                $text = str_replace("?>","", $text);

                return $text;
            }
            else 
                return $text;
        }
        else 
        {
            return $text;
        }
    }
}