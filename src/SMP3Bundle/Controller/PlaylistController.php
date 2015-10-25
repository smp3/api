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

    public function getPlaylistsAction() {
        $em = $this->getDoctrine()->getManager();
        $playlists = $em->getRepository('SMP3Bundle:Playlist')->findAll();
        $view = $this->view($playlists, 200);

        return $this->handleView($view);
    }

    public function getPlaylistAction(Playlist $playlist) {
        $view = $this->view($playlist, 200);

        return $this->handleView($view);
    }

    public function postPlaylistAction() {

        $playlist = new Playlist();

        $status = 200;
        $form = $this->createForm(new PlaylistType($this->getUser()), $playlist);
        $form->bind($this->getRequest()->get('playlist'));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $form->getData();
            $entity->setUser($this->getUser());
            $em->persist($entity);
            $em->flush();
        } else {
            $status = 500;
        }

        return $this->handleView($this->view($form, $status));
    }

    public function putPlaylistAction($id) {
        die('putPlaylistAction');
    }

    public function deletePlaylistAction($id) {
        die('deletePlaylistAction');
    }

}
