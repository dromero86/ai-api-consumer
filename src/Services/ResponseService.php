<?php  

namespace Dromero86\ApiAiConsumer\Services;

use RuntimeException; 
use Dromero86\ApiAiConsumer\Services\{ConfigService, CodeService, LogService, LogType};

class ResponseService{

    private $response;
    private CodeService $codeService;
    private ConfigService $configService; 
    private LogService $logService;  
    private $code;

    public function __construct(CodeService $codeService, ConfigService $configService, LogService $logService){
        $this->codeService = $codeService;
        $this->configService = $configService;
        $this->logService = $logService;
 
        $this->code = $this->configService->get('code');
    }

    public function setResponse($response){
        $this->response = $response;
        return $this;
    }

    public function chat_completions(){
        $body = json_decode($this->response->getBody());

        $jsonState = json_last_error();

        switch( $jsonState ){
            case JSON_ERROR_NONE: break;
            case JSON_ERROR_UTF8:
            case JSON_ERROR_UTF16:
                throw new RuntimeException("Malformed UTF-[8|16] characters, possibly incorrectly encoded");
                break;
            default: 
                throw new RuntimeException("Invalid or malformed JSON");
                break;
        }

        if(!property_exists($body, 'choices')) throw new RuntimeException('Response body hasnt choices item');
        if(empty($body->choices)) throw new RuntimeException('Response body choices is empty');
        if(!property_exists($body->choices[0], 'message')) throw new RuntimeException('Response body array hasnt message');
        if(!property_exists($body->choices[0]->message, 'content')) throw new RuntimeException('Response body message hasnt content');

        $code = $body->choices[0]->message->content;

        $this->logService->log(LogType::INFO, json_encode($body));

        return $this->codeService->distill($code);
    }

    public function save($file, $code){

        file_put_contents( $this->configService->replace($this->code->store, ['BASEPATH'=> $_ENV['BASEPATH'], 'file'=> $file ]) , $code);
    }

    public function print($code){
        die($code);
    }
}