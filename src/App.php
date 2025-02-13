<?php 

namespace Dromero86\ApiAiConsumer;

use Dromero86\ApiAiConsumer\Services\FrameworkService;

class App{

    public static function Run($directory){

        $framework = new FrameworkService();
        $framework->dotEnvLoad($directory);
        $framework->setEnvVars(["BASEPATH"=> $directory]);
        $container = $framework->dependencyInyection();
        $entrypointService = $container->get(Services\EntrypointService::class);
        $entrypointService->main(); 
    }
}