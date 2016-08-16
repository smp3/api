<?php

namespace SMP3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Entity(repositoryClass="SMP3Bundle\Entity\LibraryFileRespository")
 * @ORM\Table(name="library_file")
 */
class LibraryFile
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"library", "playlist"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Groups({"library", "playlist"})
     */
    protected $file_name;

    /**
     * @ORM\ManyToOne(targetEntity="SMP3Bundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * @ORM\OneToOne(targetEntity="SMP3Bundle\Entity\Track", orphanRemoval=true) 
     * @Groups({"library", "playlist"})
     */
    protected $track;

    /**
     * @ORM\Column(type="string")
     */
    protected $checksum;

    /**
     * @ORM\ManyToOne(targetEntity="SMP3Bundle\Entity\Album", cascade={"persist"})
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id", onDelete="CASCADE")
     *  @Groups({"library", "playlist"})
     */
    protected $album;

    /**
     * @ORM\ManyToOne(targetEntity="SMP3Bundle\Entity\Artist", cascade={"persist"})
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id", onDelete="CASCADE")
     * @Groups({"library", "playlist"})
     */
    protected $artist;
    
     /** 
      * @Accessor(getter="getTitle") 
      * @Groups({"library", "playlist"})
      */
    protected $title;

    public function getId()
    {
        return $this->id;
    }

    public function getFileName()
    {
        return $this->file_name;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getTrack()
    {
        return $this->track;
    }

    public function getChecksum()
    {
        return $this->checksum;
    }

    public function getAlbum()
    {
        return $this->album;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function setFileName($file_name)
    {
        $this->file_name = $file_name;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setTrack($track)
    {
        $this->track = $track;
    }

    public function getTitle()
    {
        //return basename($this->file_name);
        if($this->track) {
            return $this->track->getTitle();
        } else {
            return basename($this->file_name);
        }
    }

    public function setChecksum($checksum)
    {
        $this->checksum = $checksum;
    }

    public function setAlbum($album)
    {
        $this->album = $album;
    }

    public function setArtist($artist)
    {
        $this->artist = $artist;
    }
}
