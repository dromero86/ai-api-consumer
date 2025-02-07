<?php 

namespace Tero\Services;

use Garden\Cli\Cli;  
use Tero\Services\GenericService;

class EntrypointService{

    private GenericService $genericService;
    private ConfigService $configService;
    private Cli $console;

    public function __construct( GenericService $genericService, Cli $console, ConfigService $configService){
        $this->genericService = $genericService;
        $this->console = $console;
        $this->configService = $configService;
    }

    public function main(){

        $console = $this->configService->get('console');

        $this->console->description($console->message);

        foreach($console->args as $item){
            $this->console->opt("{$item->name}:{$item->key}", "{$item->details}", $item->required);
        }
        
        $args = $this->console->parse((array)$_SERVER["argv"], true);
  
        switch($args->getOpt('action'))
        {
            case "models": $this->genericService->models(); break;
            case "chat_completions":  $this->genericService->chat_completions( $args ); break;
        }
    }
}