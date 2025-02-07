<?php 

namespace Tero;

use Tero\Services\FrameworkService;

class App{

    public static function Run($directory){

        $framework = new FrameworkService();
        $framework->dotEnvLoad($directory);
        $framework->setEnvVars(["BASEPATH"=> $directory]);
        $container = $framework->dependencyInyection($directory);
        $entrypointService = $container->get(Services\EntrypointService::class);
        $entrypointService->main(); 
    }
}