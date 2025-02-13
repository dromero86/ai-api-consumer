<?php  

namespace Tero\Services;

use stdClass; 
use Tero\Services\{LogService, LogType};

class RequestService{

    private string $prompt; 
    private LogService $logService;  

    public function __construct( LogService $logService )
    { 
        $this->logService = $logService; 
    }

    public function setPrompt(string $prompt){
        $this->prompt = $prompt;
        return $this;
    }

    public function chat_completions(){
        $assistant              = new stdClass;
        $assistant->role        = "user";
        $assistant->content     = $this->prompt;

        $request                = new stdClass;
        $request->model         = $_ENV["AI_MODEL"];

        if(filter_var($_ENV['AI_HOST'], FILTER_VALIDATE_IP) !== false) 
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
 
        $this->logService->log(LogType::INFO, $this->prompt);

        return [
            "headers"   => $headers,
            "body"      => json_encode( $request )
        ];
    }
}