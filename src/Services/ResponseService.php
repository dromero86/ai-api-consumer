<?php  

namespace Tero\Services;

use stdClass;

class ResponseService{

    private $response;
    private CodeService $codeService;

    public function __construct(CodeService $codeService)
    {
        $this->codeService = $codeService;
    }

    public function setResponse($response){
        $this->response = $response;
        return $this;
    }

    public function chat_completions(){

        $body = json_decode($this->response->getBody());
        $code = $body->choices[0]->message->content;

        echo var_export($body,true);

        return $this->codeService->distill($code);

    }

    public function save($file, $code){
        file_put_contents("{$_ENV["BASEPATH"]}/{$file}", $code);
    }

    public function print($code){
        die($code);
    }
}