<?php
#
namespace SMP3Bundle\Service\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class Discover implements ConsumerInterface
{
    public function __construct($em, $libraryService) 
    {
        $this->em = $em;
        $this->libraryService = $libraryService;
    }
    
    public function execute(AMQPMessage $msg)
    {
        $messageData = (Array)json_decode($msg->getBody());
        //dump($messageData['user_id']);
        $user = $this->em->getRepository('SMP3Bundle:User')->findOneById($messageData['user_id']);
        //dump($user);

        
  
        $counter = $this->libraryService->discover($user);   

        var_dump($counter);
    }
}
