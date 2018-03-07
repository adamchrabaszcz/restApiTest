<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RestControllerTest extends WebTestCase
{
    public function testPOSTUser()
    {    
        $data = [
            'user' => [
                'name' => 'test' . rand(10, 1000),
                'password' => 'test',
                'email' => 'test@test.pl' . rand(10, 1000)
            ]
        ];
        
        $client = $this->makeRequest('/api/users', 'POST', $data);
        
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertNotEmpty($client->getResponse()->getContent());        
    }
    
    public function testPOSTInvalidUser()
    {
        $data = [
            'user' => [
                'name' => 'test' . rand(10, 1000),
                'password' => 'test'
            ]
        ];
        
        $client = $this->makeRequest('/api/users', 'POST', $data);
        
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
    
    protected function makeRequest($path, $method, $data)
    {
        $client = static::createClient();

        $crawler = $client->request(
            $method, 
            $path, 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        return $client;
    }
}
