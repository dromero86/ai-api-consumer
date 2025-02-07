<?php 

namespace Tero\Services;

use Garden\Cli\Cli;  
use Tero\Services\GenericService;

class EntrypointService{

    private GenericService $genericService;
    private Cli $console;


    public function __construct( GenericService $genericService, Cli $console)
    {
        $this->genericService = $genericService;
        $this->console = $console;
    }

    public function main(){

        $this->console->description('Acciones sobre la API LM')
            ->opt('action:a', 'Action of llm api.', true)
            ->opt('lang:l', 'Code language', false)
            ->opt('prompt:p', 'String prompt', false)
            ->opt('resource:r', 'Resource to create', false);
        
        // Parse and return cli args.
        $args = $this->console->parse((array)$_SERVER["argv"], true);
  
        switch($args->getOpt('action'))
        {
            case "models": $this->genericService->models(); break;

            case "chat_completions":  $this->genericService->chat_completions( $args ); break;
        }
    }
}