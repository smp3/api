<?php

namespace SMP3Bundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    use EntitySettings;
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
    
    /**
     * @ORM\Column(type="string", nullable=True)
     * Note: $uploadPath is relative to $path
     */
    protected $uploadPath;

    public function __construct()
    {
        parent::__construct();
        
        $this->uploadPath = "";
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
        
        return $this;
    }
    
    public function getUploadPath()
    {
        return $this->uploadPath;
    }
    
    public function setUploadPath($uploadPath)
    {
        $this->uploadPath = $uploadPath;
        
        return $this;
    }
}
