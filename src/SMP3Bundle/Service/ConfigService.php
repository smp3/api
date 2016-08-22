<?php

namespace SMP3Bundle\Service;


/**
 * TODO:
 * Get rid of that or at least make it fetch it data from db/params file at 
 * key => value basis
 */
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
