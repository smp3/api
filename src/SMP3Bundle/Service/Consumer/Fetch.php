<?php

namespace SMP3Bundle\Service\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class Fetch implements ConsumerInterface
{

    protected $ytFetchService, $libraryService, $transcodeService;


    public function __construct($doctrine, $ytFetchService, $libraryService, $transcodeService)
    {
        
        $this->em = $doctrine->getManager();
        $this->ytFetchService = $ytFetchService;
        $this->transcodeService = $transcodeService;
        
        /*
         * DO NOT use discover from libraryService - its a waste. Its better to just add
         * downloaded/converted files one by one as they go and maybe add discoverFile for that
         * so discovered files are verified/tagged.
        */
        $this->libraryService = $libraryService;
        
    }
    
    public function execute(AMQPMessage $msg)
    {
        $messageData = (Array)json_decode($msg->getBody());
       
        //dump($messageData);
        
        $user = $this->em->getRepository('SMP3Bundle:User')->findOneById($messageData['userId']);
        
        $targetDir = $user->getPath() . '/' . $user->getUploadPath();
        
        echo "Fetching...\n";
        
        $id = $this->ytFetchService->getVideoId($messageData['url']);
        $info = $this->ytFetchService->getNoEmbedInfo($id);
        
        if(array_key_exists('title', $info)) {
            $fn = $info['title'];
        } else {
            $fn = $id;
        }
        
        $fetchedFn = $this->ytFetchService->fetchVideo($messageData['url'], $targetDir, $fn);
        
        echo "Fetched to $fetchedFn \n";
     
        echo "Converting to MP3...\n";
        
        $this->transcodeService->convert($fetchedFn, $targetDir.'/'.$fn);
        
        echo "Done.\n";
    }
}
