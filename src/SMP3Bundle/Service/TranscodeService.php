<?php

namespace SMP3Bundle\Service;

use FFMpeg;

class TranscodeService
{

    public function __construct()
    {
        $this->ffmpeg = FFMpeg\FFMpeg::create();
    }

    public function convert($sourceFn, $destBaseFn)
    {
        $audio = $this->ffmpeg->open($sourceFn);
        $audio->save(new FFMpeg\Format\Audio\Mp3(), $destBaseFn.'.mp3');
    }
}
