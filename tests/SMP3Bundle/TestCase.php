<?php

namespace Tests\SMP3Bundle;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class TestCase extends WebTestCase
{
    
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    public function setUp()
    {

        parent::setUp();
        
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();

        $this->generateSchema();

      
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    protected function generateSchema()
    {
        
        $metadatas = $this->getMetadatas();

        if (!empty($metadatas)) {
            $tool = new SchemaTool($this->entityManager);
            $tool->createSchema($metadatas);
        } else {
            throw new Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
        }
        
    }

    protected function getMetadatas()
    {
        return $this->entityManager->getMetadataFactory()->getAllMetadata();
    }

    protected function loadFixtures(Array $toLoad)
    {
        foreach($toLoad as $fixture) {
            $cname = "\Tests\SMP3Bundle\Fixtures\\".$fixture;
            $fixture =  new $cname();
            $fixture->load($this->entityManager);
        }
//        $fixture = new \Tests\SMP3Bundle\Fixtures\LoadUserData();
//        $fixture->load($this->entityManager);
    }
}
