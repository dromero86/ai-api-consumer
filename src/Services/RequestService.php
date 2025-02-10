<?php  

namespace Tero\Services;

use stdClass;
use Tero\Services\ConfigService;

class RequestService{

    private string $prompt;
    private ConfigService $configService;
    private $debug;
    private $log;

    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;

        $this->debug = $this->configService->get('debug');
        $this->log = $this->configService->get('log');
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
 
        if($this->log->enable)
            file_put_contents( $this->configService->replace($this->log->file, ['BASEPATH'=> $_ENV['BASEPATH'] ]) , date('Y-m-d [H:i:s]', time()).": ".$this->prompt."\n", FILE_APPEND); 

        return [
            "headers"   => $headers,
            "body"      => json_encode( $request )
        ];
    }
}