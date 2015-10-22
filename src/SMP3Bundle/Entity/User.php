<?php

namespace SMP3Bundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=True)
     */
    protected $path;
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
    
    public function getPath() {
        return $this->path;
    }
    
    public function setPath($path) {
        $this->path = $path;
    }
}