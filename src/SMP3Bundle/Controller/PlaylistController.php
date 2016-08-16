<?php

namespace SMP3Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\View;
use SMP3Bundle\Entity\Playlist;
use SMP3Bundle\Entity\PlaylistItem;
use JMS\Serializer\SerializationContext;

/**
 * @RouteResource("")
 */
class PlaylistController extends APIBaseController implements ClassResourceInterface
{

    protected function view($data = null, $statusCode = null, array $headers = array()) {
        $view = parent::view($data, $statusCode, $headers);
        $view->setSerializationContext(SerializationContext::create()->setGroups(['playlist']));
        return $view;
    }
    
    public function getPlaylistsAction()
    {
        $playlists = $this->em->getRepository('SMP3Bundle:Playlist')->findBy(['user' => $this->getUser()]);

        $view = $this->view($playlists, 200);

        return $this->handleView($view);
    }

    public function getPlaylistAction(Playlist $playlist)
    {
        return $this->handleView($this->view($playlist, 200));
    }

    
    /**
     * {"playlist": {"title": "test pl", "items":[1, 2, 3]}}
     */
    public function postPlaylistAction(Request $request)
    {
        $data = json_decode($request->getContent());

        $playlistService = $this->get('smp3.playlist');
        
        $playlistService->validate($data);
        $playlist = new Playlist();
        $playlistService->savePlaylist($playlist, $data);
        $playlistService->saveItems($playlist, $data);
        
        $this->em->flush();
        
        return $this->handleView($this->view($playlist, 200));
    }

    public function putPlaylistAction(Request $request, Playlist $playlist)
    {
        $data = json_decode($request->getContent());
        $playlistService = $this->get('smp3.playlist');
        
        $playlistService->validate($data);
        $playlistService->savePlaylist($playlist, $data);
        $playlistService->updateItems($playlist, $data);    
        
        $this->em->flush();
        
        return $this->handleView($this->view($playlist, 200));
    }

    public function deletePlaylistAction(Playlist $playlist)
    {

        $removedId = $playlist->getId();
        
        $this->get('smp3.playlist')->delete($playlist);
        $this->em->flush();
        
        return $this->handleView($this->view(['removed_id'=>$removedId], 200));
    }
}
