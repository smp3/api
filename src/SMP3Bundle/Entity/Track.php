<?php

namespace SMP3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="track")
 */
class Track
{
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
     * @ORM\Column(type="integer", nullable=true, options={"unsigned": true, "default": 0})
     */
    protected $number;

    /**
     * @ORM\ManyToOne(targetEntity="SMP3Bundle\Entity\Album")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $album;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Track
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set number.
     *
     * @param int $number
     *
     * @return Track
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set album.
     *
     * @param \SMP3Bundle\Entity\Album $album
     *
     * @return Track
     */
    public function setAlbum(\SMP3Bundle\Entity\Album $album = null)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album.
     *
     * @return \SMP3Bundle\Entity\Album
     */
    public function getAlbum()
    {
        return $this->album;
    }
}
