<?php 

namespace Tero\Endpoints;

use \GuzzleHttp\Client;
use Tero\Services\ConfigService;

class GenericEndpoint{
    
    private Client $client;
    private ConfigService $configService;
    private string $_domain;

    function __construct(Client $client, ConfigService $configService ){
        $this->client = $client;
        $this->configService = $configService;

        $this->_domain = isset($_SERVER['AI_PORT']) ? "http://{$_SERVER['AI_HOST']}:{$_SERVER['AI_PORT']}" : "https://{$_SERVER['AI_HOST']}" ;
    }

    public function models(){

        $api = $this->configService->get('api')->models;

        $link = $this->configService->replace($api->url, [ "domain" => $this->_domain ]);

        return $this->client->request($api->method, $link);
    }

    public function chat_completions(array $data){

        $api = $this->configService->get('api')->chat_completions;

        $link = $this->configService->replace($api->url, [ "domain" => $this->_domain ]);

        return $this->client->request($api->method, $link, $data);
    }

    public function completions(array $data){

        $api = $this->configService->get('api')->completions;

        $link = $this->configService->replace($api->url, [ "domain" => $this->_domain ]);

        return $this->client->request($api->method, $link, $data);
    }

    public function embeddings(array $data){
        
        $api = $this->configService->get('api')->completions;

        $link = $this->configService->replace($api->url, [ "domain" => $this->_domain ]);

        return $this->client->request($api->method, $link, $data);
    }
}