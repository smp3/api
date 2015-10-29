<?php

namespace SMP3Bundle\Controller;


use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\MimeType\FileBinaryMimeTypeGuesser;
use SMP3Bundle\Entity\LibraryFile;
use SMP3Bundle\Entity\FileInfo;
use SMP3Bundle\Controller\APIBaseController;
use FOS\RestBundle\View\View;

/**
 * @RouteResource("")
 */
class APIController extends APIBaseController implements ClassResourceInterface {

    protected $user;
    
    public function getDirectoriesAction() {
        ///TODO: return directory tree or directories
    }

    public function getFilesAction($directory) {
        
    }

    public function getLibraryAction() {
       
        $files = $this->em->getRepository('SMP3Bundle:LibraryFile')->findAll();
;
        foreach($files as &$file) {
            $file->track_title = $file->getTrackTitle();
        }
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
        $file = $this->em->getRepository('SMP3Bundle:LibraryFile')->findOneBy(array('id' => $file_id));
        $file_name = $file->getUser()->getPath() . '/' . $file->getFileName();
        $file_contents = new File($file_name);

//        $mime_guess = new FileBinaryMimeTypeGuesser(); 
//        $mime = $mime_guess->guess($file_name);

        $response = new BinaryFileResponse($file_contents);
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }

}
