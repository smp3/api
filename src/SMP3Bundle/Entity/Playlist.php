<?php

namespace SMP3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="playlist")
 */
class Playlist {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="SMP3Bundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\OneToMany(targetEntity="SMP3Bundle\Entity\PlaylistItem", mappedBy="playlist")
     */
    protected $playlist_files;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \SMP3Bundle\Entity\User $user
     *
     * @return Playlist
     */
    public function setUser(\SMP3Bundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \SMP3Bundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Playlist
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    public function setPlaylistFiles($files) {
        $this->playlist_files = $files;
    }

    public function addPlaylistFile($file) {
        if (!is_array($this->playlist_files)) {
            $this->playlist_files = [];
        }
        $this->playlist_files[] = $file;
    }

    public function getPlaylistFiles() {
        return $this->playlist_files;
    }

}
