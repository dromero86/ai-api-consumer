<?php 

namespace Tero\Services;
 
use Garden\Cli\Args;
use Tero\Endpoints\GenericEndpoint; 
use Tero\Services\{PromptService, RequestService, ResponseService}; 

class GenericService{
    
    private $endpoint;
    private PromptService $promptService;
    private RequestService $requestService;
    private ResponseService $responseService;

    function __construct(GenericEndpoint $endpoint, PromptService $promptService, RequestService $requestService, ResponseService $responseService){
        $this->endpoint = $endpoint;
        $this->promptService = $promptService;
        $this->requestService = $requestService;
        $this->responseService = $responseService;
    }

    public function models(){
        $response = $this->endpoint->models();
        die($response->getBody());
    }

    public function chat_completions( Args $args ){

        $this->promptService->load($args->getOpt('prompt'), $args->getOpt('resource'), $args->getOpt('lang', 'php'), $_ENV['AI_REST']);

        $request  = $this->requestService->setPrompt($this->promptService->render())
                                         ->chat_completions();
                                         
        $response = $this->endpoint->chat_completions( $request ); 

        $code = $this->responseService->setResponse($response)
                                        ->chat_completions();

        $file = $this->promptService->getPath();

        if( !$file ) $this->responseService->print($code);
        
        $this->responseService->save($file, $code);
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