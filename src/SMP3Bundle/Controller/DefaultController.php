<?php

namespace SMP3Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="default_page")
     * @Route("/api")
     */
    public function defaultAction()
    {
        return new Response('SMP3');
    }
    
}
