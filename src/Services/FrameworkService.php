<?php

namespace Dromero86\ApiAiConsumer\Services;

use Dotenv\Dotenv;
use DI\ContainerBuilder;

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

    public function dependencyInyection(){

        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAutowiring(true); 
        $containerBuilder->addDefinitions([
            'directories' => [ dirname( __DIR__ ) ]
        ]);
        
        return $containerBuilder->build();
    }
}