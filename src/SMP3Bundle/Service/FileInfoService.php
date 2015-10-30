<?php

namespace SMP3Bundle\Service;

use Symfony\Component\HttpFoundation\File\File;

class FileInfoService {

    protected $tag_info = null;
    protected $set_tags = ['track_number', 'artist', 'title', 'album'];

    public function __construct() {
        $this->tag_info = new \getID3;
    }

    public function addTrackTitles(&$files) {
        foreach($files as &$file) {
            $file->track_title = $file->getTrackTitle();
        }
        return $files;
    }
    
    public function getTagInfo($file_path) {
        $info = $this->tag_info->analyze($file_path);
        \getid3_lib::CopyTagsToComments($info);

        $comments = [];

        if (array_key_exists('comments', $info)) {

            
            foreach ($info['comments'] as $key => $comment) {
                
                if(!in_array($key, $this->set_tags)) {
                    continue;
                }
                
                if (is_array($comment) && count($comment) == 1) {
                    $comments[$key] = $comment[0];
                } else {
                    $comments[$key] = $comment;
                }
            }


            if (array_key_exists('track_number', $comments) && strstr($comments['track_number'], '/')) {
                $parts = explode('/', $comments['track_number']);
                if (count($parts)==2 && is_numeric($parts[0])) {
                    $comments['track_number'] = $parts[0];
                }
            }
            $empty_count = 0;
            foreach ($this->set_tags as $tag) {
                if (!array_key_exists($tag, $comments)) {
                    $comments[$tag] = null;
                    $empty_count ++;
                }
            }
            
            if($empty_count == count($this->set_tags)) {
                $comments = null;
            }

            return $comments;
        } else {
            return null;
        }
    }

}
