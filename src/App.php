<?php 

namespace Tero;

use Garden\Cli\Cli; 
use Tero\Endpoints\GenericEndpoint;
use Tero\Services\GenericService;

class App{

    public static function Run(){

        $cli = new Cli();

        $cli->description('Acciones sobre la API LM')
            ->opt('action:a', 'Action of llm api.', true)
            ->opt('lang:l', 'Code language', false)
            ->opt('prompt:p', 'String prompt', false)
            ->opt('file:f', 'File path store', false);
        
        // Parse and return cli args.
        $args = $cli->parse((array)$_SERVER["argv"], true);
 
        $endpoint = new GenericEndpoint();
        $service = new GenericService( $endpoint );

        switch($args->getOpt('action'))
        {
            case "models": $service->models(); break;

            case "chat_completions": 
                $body = $service->chat_completions( $args ); 
                die("\n{$body}\n");
            break;
        }
    }
}