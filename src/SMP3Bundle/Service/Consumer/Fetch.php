<?php

namespace SMP3Bundle\Service\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use SMP3Bundle\Service\YTService;
use SMP3Bundle\Service\LibraryService;
use SMP3Bundle\Service\TranscodeService;

class Fetch implements ConsumerInterface
{

    protected $ytFetchService, $libraryService, $transcodeService;

    public function __construct($doctrine, YTService $ytFetchService, LibraryService $libraryService, TranscodeService $transcodeService)
    {

        $this->em = $doctrine->getManager();
        $this->ytFetchService = $ytFetchService;
        $this->transcodeService = $transcodeService;
        $this->libraryService = $libraryService;
        
    }

    public function execute(AMQPMessage $msg)
    {
        $messageData = (Array) json_decode($msg->getBody());

        //dump($messageData);

        $user = $this->em->getRepository('SMP3Bundle:User')->findOneById($messageData['userId']);

        $targetDir = $user->getPath() . '/' . $user->getUploadPath();

        echo "Fetching...\n";

        $id = $this->ytFetchService->getVideoId($messageData['url']);
        $info = $this->ytFetchService->getNoEmbedInfo($id);

        if (array_key_exists('title', $info)) {
            $fn = $info['title'];
        } else {
            $fn = $id;
        }

        $fetchedFn = $this->ytFetchService->fetchVideo($messageData['url'], $targetDir, $fn);

        echo "Fetched to $fetchedFn \n";

        $transcodeFormat = $messageData['transcodeFormat'];
        
        echo "Converting to $transcodeFormat...\n";

        $filePath = $this->transcodeService->convert($fetchedFn, $targetDir . '/' . $fn);
        $this->libraryService->addLibraryFile($user, $filePath, 'mp3', true);
        $this->em->flush();

        echo "Done.\n";
    }
}
