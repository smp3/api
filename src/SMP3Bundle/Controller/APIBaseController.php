<?php

namespace SMP3Bundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class APIBaseController extends FOSRestController
{

    protected $em;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->em = $this->getDoctrine()->getManager();
    }

    protected function getPagingInfo(Request $request)
    {
        return [
            'max' => $request->get('max', $this->getParameter('library_item_limit')),
            'from' => $request->get('from', 0),
        ];
    }
}
