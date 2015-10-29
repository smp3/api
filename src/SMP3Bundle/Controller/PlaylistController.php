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
         
//        foreach($playlists as $playlist) {
//            $files = $playlist->getPlaylistFiles();
//            $playlist->setPlaylistFiles($this->container->get('FileInfoService')->addTrackTitles($files));
//        }
        
        $view = $this->view($playlists, 200);

        return $this->handleView($view);
    }

    public function getPlaylistAction(Playlist $playlist) {
        $view = $this->view($playlist, 200);

        return $this->handleView($view);
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
        
        foreach ($data->playlist->items as $item) {
            $playlist_item = new PlaylistItem();
            $file = $em->getRepository('SMP3Bundle:LibraryFile')->findOneBy(['id' => $item->file_id]);
            $playlist_item->setFile($file);
            $playlist_item->setPlaylist($playlist);
            $em->persist($playlist_item);
            $playlist->addPlaylistFile($playlist_item);
        }

        $em->persist($playlist);
        $em->flush();

        return $this->handleView($this->view("OK", 200));
        
    }

    public function putPlaylistAction($id) {
        die('putPlaylistAction');
    }

    public function deletePlaylistAction(Playlist $playlist) {
        $em = $this->getDoctrine()->getManager();
        foreach($playlist->getPlaylistFiles() as $file) {
            $em->remove($file);
        }
        $em->remove($playlist);
        $em->flush();
        return $this->handleView($this->view("OK", 200)); 
    }

}
