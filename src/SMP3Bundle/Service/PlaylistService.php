<?php

namespace SMP3Bundle\Service;

use SMP3Bundle\Entity\Playlist;
use SMP3Bundle\Entity\PlaylistItem;
Use SMP3Bundle\Entity\User;

class PlaylistService
{

    public function __construct($em, $tokenStorage)
    {
        $this->em = $em->getManager();
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function delete(Playlist $playlist)
    {

        if ($playlist->getUser() != $this->user) {
            ///// Error here
        }


        $this->em->remove($playlist);
        //
    }

    public function create($data)
    {
        // TODO
    }
    
    public function saveItems(Playlist $playlist, $data)
    {
        /*
         * Disgrepancy: POST has playlist_files, PUT has items
         */
        
        foreach ($data->playlist->items as $item) {
            $playlist_item = new PlaylistItem();
            $item = $item->file;
            $file = $this->em->getRepository('SMP3Bundle:LibraryFile')->findOneBy(['id' => $item->id]);
            $playlist_item->setFile($file);
            $playlist_item->setPlaylist($playlist);
            $this->em->persist($playlist_item);
            $playlist->addItem($playlist_item);
        }

        $this->em->persist($playlist);
    }

    public function updateItems(Playlist $playlist, $data)
    {
        foreach ($playlist->getItems() as $file) {
            $this->em->remove($file);
        }
        
        $this->saveItems($playlist, $data);
    }

    public function savePlaylist(Playlist $playlist, $data)
    {
        $playlist->setTitle($data->playlist->title);
        $playlist->setUser($this->user);
        $this->em->persist($playlist);
    }

    public function validate($data)
    {
        if (empty($data) || !array_key_exists('playlist', $data)) {
            throw new \Exception('Bad params');
        }
    }
}
