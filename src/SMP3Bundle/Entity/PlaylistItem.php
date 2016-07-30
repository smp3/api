<?php

namespace SMP3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="playlist_item")
 */
class PlaylistItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="SMP3Bundle\Entity\Playlist", inversedBy="playlist_files")
     * @ORM\JoinColumn(name="playlist_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $playlist;

    /**
     * @ORM\ManyToOne(targetEntity="SMP3Bundle\Entity\LibraryFile")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $file;

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
     * Set playlist.
     *
     * @param \SMP3Bundle\Entity\Playlist $playlist
     *
     * @return PlaylistItem
     */
    public function setPlaylist(\SMP3Bundle\Entity\Playlist $playlist)
    {
        $this->playlist = $playlist;

        return $this;
    }

    /**
     * Get playlist.
     *
     * @return \SMP3Bundle\Entity\Playlist
     */
    public function getPlaylist()
    {
        return $this->playlist;
    }

    /**
     * Set file.
     *
     * @param \SMP3Bundle\Entity\LibraryFile $file
     *
     * @return PlaylistItem
     */
    public function setFile(\SMP3Bundle\Entity\LibraryFile $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file.
     *
     * @return \SMP3Bundle\Entity\LibraryFile
     */
    public function getFile()
    {
        return $this->file;
    }
}
