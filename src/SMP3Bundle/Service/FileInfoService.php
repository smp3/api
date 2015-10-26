<?php

namespace SMP3Bundle\Service;

use Symfony\Component\HttpFoundation\File\File;

class FileInfoService {

    protected $tag_info = null;

    public function __construct() {
        $this->tag_info = new \getID3;
    }
    
    public function getTagInfo($file_path) {
        $info = $this->tag_info->analyze($file_path);
        \getid3_lib::CopyTagsToComments($info);
        
        $comments = [];
        
        foreach($info['comments'] as $key=>$comment) {
            if(is_array($comment) &&  count($comment)==1) {
                $comments[$key]=$comment[0];
            } else {
                $comments[$key]=$comment;
            }
            
        }
        
        
        if($comments['track_number'] && strstr($comments['track_number'], '/')) {
            $parts = explode('/', $comments['track_number']);
            if(is_numeric($parts[0])) {
                $comments['track_number']=$parts[0];
            }
        }
        //var_dump($comments);
        return $comments;
    }

}
