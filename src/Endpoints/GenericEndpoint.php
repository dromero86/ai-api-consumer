<?php 

namespace Tero\Endpoints;

use \GuzzleHttp\Client;

class GenericEndpoint{
    
    private Client $client;
    private string $_domain;

    function __construct(Client $client){
        $this->client = $client;

        if( isset($_SERVER['AI_PORT']) )
            $this->_domain = "http://{$_SERVER['AI_HOST']}:{$_SERVER['AI_PORT']}";
        else
            $this->_domain = "https://{$_SERVER['AI_HOST']}";
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