<?php

namespace Tero\Services;

use Dotenv\Dotenv;

class FrameworkService {

    public function setEnvVars($data){
        foreach($data as $key=>$value){
            $_ENV[$key]=$value;
        }
    }

    public function dotEnvLoad($directory){
        $dotenv = Dotenv::createImmutable($directory);
        $dotenv->load();
    }
}