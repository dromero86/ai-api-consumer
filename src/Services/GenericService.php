<?php 

namespace Tero\Services;

use Garden\Cli\Args;
use Tero\Endpoints\GenericEndpoint;
use stdClass;

class GenericService{
    
    private $endpoint;

    function __construct(GenericEndpoint $endpoint){
        $this->endpoint = $endpoint;
    }

    public function models(){
        $response = $this->endpoint->models();
        die($response->getBody());
    }

    public function chat_completions( Args $args ){


        $prompt = new PromptService();
        $resource = $prompt->resourceDecode($args->getOpt('resource'));


        $assistant              = new stdClass;
        $assistant->role        = "user";
        $assistant->content     = "{$args->getOpt('prompt')}. Lenguaje: {$args->getOpt('lang')} . {$_ENV['AI_REST']}";

        $request                = new stdClass;
        $request->model         = $_ENV["AI_MODEL"];
        $request->temperature   = 0.7;
        $request->max_tokens    = -1;
        $request->stream        = false;
        $request->messages      = [ $assistant ]; 

        $response = $this->endpoint->chat_completions([
            "headers"   => [ 'Content-Type' => 'application/json' ],
            "body"      => json_encode( $request ), 
            //'connect_timeout' => 30
        ]); 

        $body = json_decode($response->getBody());
        $code = $body->choices[0]->message->content;

        $code = CodeService::Distill($code);

        $file = $args->getOpt('file', FALSE);

        if( $file ){
            file_put_contents("{$_ENV["BASEPATH"]}/{$file}", $code);
        }
    }

    public function completions(array $data){
        $response = $this->endpoint->completions($data);
        die($response->getBody());
    }

    public function embeddings(array $data){
        $response = $this->endpoint->embeddings($data);
        die($response->getBody());
    }
}