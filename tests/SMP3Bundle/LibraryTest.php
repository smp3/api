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
        $client = $this->makeTokenRequest('GET','/api/library', []);
        $response = $client->getResponse();
        $content = json_decode($response->getContent());
        dump($content);
        $this->assertEquals($response->getStatusCode(), 200);
        
    }
}
