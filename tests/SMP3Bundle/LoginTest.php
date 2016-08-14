<?php

namespace Tests\SMP3Bundle;

class LoginTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
          $this->loadFixtures(['LoadUserData']);
    }

    public function testBadLogin() 
    {
        
        $this->client->request('POST', '/api/login_check', [
            '_username' => 'testUser',
            '_password' => 'wrongPass',
            ], [], ['CONTENT_TYPE' => 'application/json']
        );

        
        $content = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals($content->code, 401);
    }
    
    public function testLogin()
    {
        
        $this->client->request('POST', '/api/login_check', [
            '_username' => 'testUser',
            '_password' => 'testPass',
            ], [], ['CONTENT_TYPE' => 'application/json']
        );
        $response = $this->client->getResponse();
        $content = json_decode($response->getContent());
        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertEquals(empty($content->token), false);
      
    }
}
