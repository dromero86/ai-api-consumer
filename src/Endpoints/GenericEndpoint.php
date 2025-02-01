<?php 

namespace Tero\Endpoints;
 

class GenericEndpoint{
    
    private $client;
    private $_domain;

    function __construct(){
        $this->client = new \GuzzleHttp\Client(/*[ "timeout"=> 10 ]*/);
        $this->_domain = "http://{$_SERVER['AI_HOST']}:{$_SERVER['AI_PORT']}";
    }

    public function models(){
        return $this->client->request('GET', "{$this->_domain}/v1/models");  
    }

    public function chat_completions(array $data){
        return $this->client->request('POST', "{$this->_domain}/v1/chat/completions", $data);
    }

    public function completions(array $data){
        return $this->client->request('POST', "{$this->_domain}/v1/completions", $data);
    }

    public function embeddings(array $data){
        return $this->client->request('POST', "{$this->_domain}/v1/embeddings", $data);
    }
}