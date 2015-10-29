<?php

namespace SMP3Bundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\MimeType\FileBinaryMimeTypeGuesser;
use SMP3Bundle\Entity\LibraryFile;
use SMP3Bundle\Entity\FileInfo;
use FOS\RestBundle\View\View;

/**
 * @RouteResource("")
 */
class APIController extends FOSRestController implements ClassResourceInterface {

    protected $user;
    
    public function getDirectoriesAction() {
        ///TODO: return directory tree or directories
    }

    public function getFilesAction($directory) {
        
    }

    public function getLibraryAction() {
        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository('SMP3Bundle:LibraryFile')->findAll();
        //$view = $this->view($files, 200);
        $view = View::create();
        $view->setData($files);
 
        return $this->handleView($view);
        
    }

    public function getDiscoverAction() {

        $library_service = $this->container->get('LibraryService');
        
        $counter = $library_service->discover($this->getUser());

        return $this->handleView($this->view($counter, 200));
    }

    public function getStreamAction($file_id) {
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('SMP3Bundle:LibraryFile')->findOneBy(array('id' => $file_id));
        $file_name = $file->getUser()->getPath() . '/' . $file->getFileName();
        $file_contents = new File($file_name);

//        $mime_guess = new FileBinaryMimeTypeGuesser(); 
//        $mime = $mime_guess->guess($file_name);

        $response = new BinaryFileResponse($file_contents);
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }

}
