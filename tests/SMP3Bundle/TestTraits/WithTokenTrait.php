<?php

namespace Tests\SMP3Bundle\TestTraits;

trait WithTokenTrait
{

    private $token = null;

    protected function getToken()
    {

        if ($this->token == null) {
            $this->client->request('POST', '/api/login_check', [
                '_username' => 'testUser',
                '_password' => 'testPass',
                ], [], ['CONTENT_TYPE' => 'application/json']
            );
            $response = $this->client->getResponse();
            $content = $response->getContent();

            $data = json_decode($content);

            $this->token = $data->token;
        }
        return $this->token;
    }

    protected function makeTokenRequest($method, $url, $data)
    {
        $this->client->request($method, $url, $data, [], [
            'HTTP_AUTHORIZATION' => "Bearer {$this->getToken()}",
            'CONTENT_TYPE' => 'application/json',
        ]);

        return $this->client;
    }
}
