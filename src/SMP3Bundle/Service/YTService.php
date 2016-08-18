<?php

namespace SMP3Bundle\Service;


class YTService
{

    const WATCH_PART = "https://www.youtube.com/watch?v=";

    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey; //No embed will be used, no need for apiKey
    }

    protected function findStreamData(Array $streams, $format)
    {
        foreach ($streams as $stream) {
            $data = [];
            parse_str($stream, $data);

            if (stripos($data['type'], $format) !== false) {
                return $data;
            }
        }
    }

    public function getNoEmbedInfo($id)
    {
        $url = 'https://noembed.com/embed?url=https://www.youtube.com/watch?v=' . $id . '&alt=json';
        $info = json_decode(file_get_contents($url));
        return (Array) $info;
    }

    public function getVideoId($url)
    {
        $id = str_replace(YTService::WATCH_PART, "", $url);
        return $id;
    }

    public function getVideoInfo($url)
    {
        $id = $this->getVideoId($url);

        $info = [];
        $contents = file_get_contents("http://youtube.com/get_video_info?video_id=" . $id);
        parse_str($contents, $info);

        return $info;
    }

    public function fetchVideo($url, $targetDir, $baseFn, $format = 'mp4')
    {

        $info = $this->getVideoInfo($url);

        $streams = explode(',', $info['url_encoded_fmt_stream_map']);

        $data = $this->findStreamData($streams, $format);
        $video = fopen($data['url'], 'r');

        $fn = $targetDir . '/' . $baseFn . '.' . $format;
        
        $file = fopen($fn, 'w');
        stream_copy_to_stream($video, $file); 
        fclose($video);
        fclose($file);

        return $fn;
    }
}
