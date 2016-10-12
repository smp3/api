<?php

namespace SMP3Bundle\Service;


class TaskService
{
    protected  $em;

    public function __construct($em)
    {
        $this->em = $em;
    }
}