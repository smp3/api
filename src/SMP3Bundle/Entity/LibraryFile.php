<?php

namespace SMP3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="library_file")
 */
class LibraryFile {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $file_name;

    
    /**
     * @ORM\ManyToOne(targetEntity="SMP3Bundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    public function getId() {
        return $this->id;
    }

    public function getFileName() {
        return $this->file_name;
    }

    function getUser() {
        return $this->user;
    }
    
    public function setFileName($file_name) {
        $this->file_name = $file_name;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    function setUser($user) {
        $this->user = $user;
    }
    
   

}
