<?php

namespace SMP3Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use SMP3Bundle\Controller\APIBaseController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\View\View;
use SMP3Bundle\Entity\Playlist;
use SMP3Bundle\Entity\PlaylistItem;
use SMP3Bundle\Form\PlaylistType;

/**
 * @RouteResource("")
 */
class PlaylistController extends APIBaseController implements ClassResourceInterface {

    public function getPlaylistsAction() {

        $playlists = $this->em->getRepository('SMP3Bundle:Playlist')->findAll();

        $view = $this->view($playlists, 200);

        return $this->handleView($view);
    }

    public function getPlaylistAction(Playlist $playlist) {
        return $this->handleView($this->view($playlist, 200));
    }

    public function postPlaylistAction(Request $request) {


        $em = $this->getDoctrine()->getManager();
       
        $data = json_decode($request->getContent());
        
        if (empty($data) || !array_key_exists('playlist', $data)) {
            throw new \Exception("Bad params");
        }

        $playlist = new Playlist();

        $playlist->setTitle($data->playlist->title);
        $em->persist($playlist);
        $em->flush();

        foreach ($data->playlist->playlist_files as $item) {
            $playlist_item = new PlaylistItem();
            $item = $item->file;
            $file = $em->getRepository('SMP3Bundle:LibraryFile')->findOneBy(['id' => $item->id]);
            $playlist_item->setFile($file);
            $playlist_item->setPlaylist($playlist);
            $em->persist($playlist_item);
            $playlist->addPlaylistFile($playlist_item);
        }

        $em->persist($playlist);
        $em->flush();

        return $this->handleView($this->view("OK", 200));
    }

    public function putPlaylistAction(Request $request, Playlist $playlist) {

        foreach($playlist->getPlaylistFiles() as $file) {
           $this->em->remove($file); 
        }
        
        $this->em->remove($playlist);
        $this->em->flush();
        
        return $this->postPlaylistAction($request);
    }

    public function deletePlaylistAction(Playlist $playlist) {
        $em = $this->getDoctrine()->getManager();
        foreach ($playlist->getPlaylistFiles() as $file) {
            $em->remove($file);
        }
        $em->remove($playlist);
        $em->flush();
        return $this->handleView($this->view("OK", 200));
    }

}
