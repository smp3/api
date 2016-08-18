<?php

namespace SMP3Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;

use SMP3Bundle\Entity\Task;

/**
 * @RouteResource("tasks")
 */
class TaskController extends APIBaseController implements ClassResourceInterface
{

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->taskRepository = $this->em->getRepository('SMP3Bundle:Task');
    }

    public function getTaskStatus(Task $task)
    {
        
    }

    public function getTasks(Request $request)
    {
        
    }
}
