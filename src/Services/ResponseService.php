<?php  

namespace Tero\Services;

use Tero\Services\ConfigService;

class ResponseService{

    private $response;
    private CodeService $codeService;
    private ConfigService $configService; 
    private $log;

    public function __construct(CodeService $codeService, ConfigService $configService){
        $this->codeService = $codeService;
        $this->configService = $configService;
        $this->log = $this->configService->get('log');
    }

    public function setResponse($response){
        $this->response = $response;
        return $this;
    }

    public function chat_completions(){
        $body = json_decode($this->response->getBody());
        $code = $body->choices[0]->message->content;

        if($this->log->enable)
            file_put_contents( $this->configService->replace($this->log->file, ['BASEPATH'=> $_ENV['BASEPATH'] ]) , date('Y-m-d [H:i:s]', time()).": ".json_encode($body)."\n", FILE_APPEND); 

        return $this->codeService->distill($code);
    }

    public function save($file, $code){
        file_put_contents("{$_ENV["BASEPATH"]}/{$file}", $code);
    }

    public function print($code){
        die($code);
    }
}