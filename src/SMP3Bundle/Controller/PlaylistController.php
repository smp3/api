<?php

namespace SMP3Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\View;
use SMP3Bundle\Entity\Playlist;
use SMP3Bundle\Form\PlaylistType;

/**
 * @RouteResource("")
 */
class PlaylistController extends FOSRestController implements ClassResourceInterface {

    public function getPlaylistAction(Playlist $playlist) {
        $view = $this->view($playlist, 200);

        return $this->handleView($view);
    }

    public function postPlaylistAction(Request $request) {

        $form = $this->createForm(new PlaylistType($this->getUser()));
        $form->bind($request);
        
        if($form->isValid()) {
            die('valid');
        } else {
            var_dump($form->getErrors());
            die('not valid');
        }
        
        
        return $this->handleView($view);
        //die('postPlaylistAction');
    }

    public function putPlaylistAction($id) {
        die('putPlaylistAction');
    }

    public function deletePlaylistAction($id) {
        die('deletePlaylistAction');
    }

}
