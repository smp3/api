<?php

namespace SMP3Bundle\Service;

class ConfigService
{
    public function __construct($discoverableExts)
    {
        $this->discoverableExts = $discoverableExts;
    }

    public function getDiscoverableExts()
    {
        return $this->discoverableExts;
    }
}
