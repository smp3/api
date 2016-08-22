<?php

namespace SMP3Bundle\Service;

class ChecksumService
{
    public function checksum($contents)
    {
        return crc32($contents);
    }
    
    public function fileChecksum($fileName)
    {
         $contents = file_get_contents($fileName);
         return $this->checksum($contents);
    }
    
    public function deduceChecksumType($checksum)
    {
        //TODO
    }
}