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

/**
 * @RouteResource("")
 */
class APIController extends FOSRestController implements ClassResourceInterface {
    protected $user;
  
    protected function deleteEntity($entity, $key, $em) {
        $em->remove($entity);
    }

    public function getLibraryAction() {
        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository('SMP3Bundle:LibraryFile')->findAll();
        $view = $this->view($files, 200);
        
        return $this->handleView($view);
    }

    public function getDiscoverAction() {
       
        $finder = new Finder();
        $finder->files()->in($this->getUser()->getPath());
        $em = $this->getDoctrine()->getManager();
        $all = $em->getRepository('SMP3Bundle:LibraryFile')->findAll();
        

        if (count($all)) {
            array_walk($all, array($this, 'deleteEntity'), $em);
            $em->flush();
        }

        foreach ($finder as $file) {
            $lf = new LibraryFile();
            $lf->setFileName($file->getRelativePathname());
            $lf->setUser($this->getUser());
            $em->persist($lf);
        }

        $em->flush();

        return $this->handleView($this->view("OK", 200));
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
