<?php

namespace SMP3Bundle\Controller;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;

/**
 * @RouteResource("fetch")
 */
class FetchController extends APIBaseController implements ClassResourceInterface
{

    /**
     * @Post("fetch/yt")
     */
    public function postYTAction(Request $request)
    {

     

        $msg = [
            'url' => $request->get('url'),
            'userId' => $this->getUser()->getId(),
        ];

        $this
            ->get('old_sound_rabbit_mq.fetch_producer')
            ->publish(json_encode($msg))
        ;

        return $this->handleView($this->view(['ok' => true]));
    }
}
