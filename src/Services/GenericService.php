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

        $prompt = new PromptService($args->getOpt('prompt'), $args->getOpt('resource'), $args->getOpt('lang', 'php'), $_ENV['AI_REST']);

        $assistant              = new stdClass;
        $assistant->role        = "user";
        $assistant->content     = $prompt->render();

        $request                = new stdClass;
        $request->model         = $_ENV["AI_MODEL"];

        if( isset($_SERVER['AI_PORT']) )
        {
            $request->temperature   = 0.7;
            $request->max_tokens    = -1;
        }
        
        $request->stream        = false;
        $request->store         = true;
        $request->messages      = [ $assistant ]; 

        $headers = [];

        $headers[ 'Content-Type' ]= 'application/json';

        if(isset($_ENV['AI_TOKEN'])){
            $headers[ 'Authorization' ]= "Bearer {$_ENV['AI_TOKEN']}";            
        }

        $response = $this->endpoint->chat_completions([
            "headers"   => $headers,
            "body"      => json_encode( $request )
        ]); 

        $body = json_decode($response->getBody());
        $code = $body->choices[0]->message->content;

        $code = CodeService::Distill($code);

        $file = $prompt->getPath();

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