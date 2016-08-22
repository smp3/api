<?php

namespace SMP3Bundle\Controller;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;

/**
 * @RouteResource("fetch")
 */
class FetchController extends APIBaseController implements ClassResourceInterface
{

    /**
     * @Get("fetch/yt")
     */
    public function getYTInfoAction(Request $request)
    {
        $url = $request->get('url');

        $ytFetchService = $this->get('smp3.ytfetch');

        $id = $ytFetchService->getVideoId($url);
        $ytinfo = $ytFetchService->getVideoInfo($url);
        $ninfo = $ytFetchService->getNoEmbedInfo($id);

        $info = [
            'ytinfo' => $ytinfo,
            'info' => $ninfo,
        ];

        return $this->handleView($this->view($info, 200));
    }

    /**
     * @Post("fetch/yt")
     */
    public function postYTAction(Request $request)
    {
        $transcodeService = $this->get('smp3.transcode');
        $transcodeFormat = $request->get('transcode_format', null);

        if ($transcodeFormat && !$transcodeService->hasFormat($transcodeFormat)) {
            $message = sprintf('Transcoding to %s format is not supported', $transcodeFormat);
            return $this->handleView($this->view(['ok' => false, 'message' => $message], 500));
        }




        $msg = [
            'url' => $request->get('url'),
            'transcodeFormat' => $transcodeFormat,
            'userId' => $this->getUser()->getId(),
        ];

        $this
            ->get('old_sound_rabbit_mq.fetch_producer')
            ->publish(json_encode($msg))
        ;

        return $this->handleView($this->view(['ok' => true]));
    }
}
