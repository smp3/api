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

    /**
     * @ORM\OneToOne(targetEntity="SMP3Bundle\Entity\FileInfo", orphanRemoval=true) 
     */
    protected $info;
    
    public $track_title;

    public function getId() {
        return $this->id;
    }

    public function getFileName() {
        return $this->file_name;
    }

    public function getUser() {
        return $this->user;
    }

    public function getInfo() {
        return $this->info;
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

    public function setInfo($info) {
        $this->info = $info;
    }
    
    public function getTrackTitle() {
        if($this->info) {
            return $this->info->getTitle();
        } else {
            return basename($this->file_name);
        }
    }

}
