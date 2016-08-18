<?php

namespace SMP3Bundle\Service;

class YTFetchService
{

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

    public function fetchVideo($url, $targetDir, $format = 'mp4')
    {
        $watchPart = "https://www.youtube.com/watch?v=";

        $id = str_replace($watchPart, "", $url);

        $info = [];

        $contents = file_get_contents("http://youtube.com/get_video_info?video_id=" . $id);
        //TODO: try to fetch the title from $info
        
        parse_str($contents, $info);
        $streams = $info['url_encoded_fmt_stream_map'];
        $streams = explode(',', $streams);

        $data = $this->findStreamData($streams, $format);
        $video = fopen($data['url'], 'r');
        
        $fn = $targetDir . '/' . md5(time()) . '.' . $format;
        $file = fopen($fn, 'w');
        
        stream_copy_to_stream($video, $file); //copy it to the file
        fclose($video);
        fclose($file);


        //echo "$id done \n";
        return $fn;
    }
}
