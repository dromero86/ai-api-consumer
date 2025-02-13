<?php 

namespace Dromero86\ApiAiConsumer\Services;

use Garden\Cli\Cli;
use RuntimeException;
use Dromero86\ApiAiConsumer\Services\GenericService;

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
        
        if(!isset($_SERVER["argv"])) throw new RuntimeException("Console input not found (argv)"); 

        $args = $this->console->parse((array)$_SERVER["argv"], true);
  
        $action = $args->getOpt('action');

        if($action == "models") 
            $this->genericService->{$action}();
        else
            if ( in_array($action,["chat_completions","completions","embeddings"]) )
                $this->genericService->{$action}($args);
            else 
                throw new RuntimeException("Action {$action} not found");
    }
}