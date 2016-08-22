<?php

namespace SMP3Bundle\Service;

use FFMpeg;

class TranscodeService
{

    protected $formatClass;

    public function __construct()
    {
        $this->ffmpeg = FFMpeg\FFMpeg::create();
        $this->formatClass = [
            'mp3' => 'FFMpeg\Format\Audio\Mp3',
            'ogg' => 'FFMpeg\Format\Audio\Vorbis',
        ];
    }

    public function getAvailableFormats()
    {
        return array_keys($this->formatClass);
    }

    public function hasFormat($format)
    {
        return array_key_exists($format, $this->formatClass);
    }

    public function convert($sourceFn, $destBaseFn)
    {
        $audio = $this->ffmpeg->open($sourceFn);

        $destFn = $destBaseFn . '.mp3';

        $audio->save(new FFMpeg\Format\Audio\Mp3(), $destFn);
        return $destFn;
    }
}
