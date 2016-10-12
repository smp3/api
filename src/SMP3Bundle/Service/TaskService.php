<?php

namespace SMP3Bundle\Service;
use SMP3Bundle\Entity\User;
use SMP3Bundle\Entity\Task;

class TaskService
{
    protected  $em;
    
    
    
    protected  function typeToServiceName($type) {
        $typeService = [
        'discover'=>'old_sound_rabbit_mq.discover_producer',
        'fetch'=>'smp3.consumer.fetch',
    ];
        if(array_key_exists($type, $typeService)) {
            return $typeService[$type];
        }
    }
    
    public function __construct($em, $container)
    {
        $this->em = $em;
        $this->container = $container;
    }
    
    public function enqueueTask(User $user, $type)
    {
        $task = new Task($type, $user);
        $this->em->persist($task);
        $this->em->flush();
        
        $serviceName = $this->typeToServiceName($type);
        dump($serviceName);
        die;
    }
}