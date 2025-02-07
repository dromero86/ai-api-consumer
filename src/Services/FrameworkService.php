<?php

namespace Tero\Services;

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

    public function dependencyInyection($directory){
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAutowiring(true); 
        
        // Configuración de directorios sin Symfony
        $containerBuilder->addDefinitions([
            'directories' => [
                $directory . '/src'  // Tu directorio de clases
            ]
        ]);
        
        return $containerBuilder->build();
    }
}