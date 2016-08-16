<?php

namespace Tests\SMP3Bundle;

class LibraryTest extends TestCase
{

    use \Tests\SMP3Bundle\TestTraits\WithTokenTrait;

    public function setUp()
    {
        parent::setUp();
        $this->loadFixtures(['LoadUserData', 'LoadLibraryData']);
    }

    public function testGetAll()
    {
         
        $this->getToken();
//        $this->client->request('GET', '/api/library', [], [], [
//            'HTTP_AUTHORIZATION' => "Bearer {$this->getToken()}",
//            'CONTENT_TYPE' => 'application/json',
//        ]);
//        
//        //$client = $this->makeTokenRequest('GET','/api/library', []);
//        $response = $this->client->getResponse();
//        $content = json_decode($response->getContent());
//        dump($response->getContent());
//        $this->assertEquals($response->getStatusCode(), 200);
        
    }
}
