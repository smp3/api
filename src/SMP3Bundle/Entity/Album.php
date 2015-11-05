<?php

namespace SMP3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="SMP3Bundle\Entity\AlbumRepository")
 * @ORM\Table(name="album")
 */
class Album {
     use EntitySettings;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $title;
    
    /**
     * @ORM\ManyToOne(targetEntity="SMP3Bundle\Entity\Artist")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $artist;
    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Album
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set artist
     *
     * @param \SMP3Bundle\Entity\Artist $artist
     *
     * @return Album
     */
    public function setArtist(\SMP3Bundle\Entity\Artist $artist = null)
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get artist
     *
     * @return \SMP3Bundle\Entity\Artist
     */
    public function getArtist()
    {
        return $this->artist;
    }
}
