<?php

namespace SMP3Bundle\Service;

class LibraryService {
    protected $container;
    
    public function __construct($container) {
        $this->container = $container;
    }
}