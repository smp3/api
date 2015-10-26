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

/**
 * @RouteResource("")
 */
class APIController extends FOSRestController implements ClassResourceInterface {

    protected $user;
    protected $exts = ['mp3', 'mp4', 'ogg', 'm4a'];

    protected function deleteEntity($entity, $key, $em) {
        $em->remove($entity);
    }

    public function getDirectoriesAction() {
        ///TODO: return directory tree or directories
    }

    public function getFilesAction($directory) {
        
    }

    public function getLibraryAction() {
        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository('SMP3Bundle:LibraryFile')->findAll();
        $view = $this->view($files, 200);

        return $this->handleView($view);
    }

    public function getDiscoverAction() {
  
        $info_service = $this->container->get('FileInfoService');
        $finder = new Finder();
        $finder->files()->in($this->getUser()->getPath());
        $em = $this->getDoctrine()->getManager();
        $all = $em->getRepository('SMP3Bundle:LibraryFile')->findAll();

        $info = new \stdClass();

        if (count($all)) {
            array_walk($all, array($this, 'deleteEntity'), $em);
            $em->flush();
        }

        $counter = 0;
        foreach ($finder as $file) {
            if (!in_array($file->getExtension(), $this->exts)) {
                continue;
            }

            $info_data = $info_service->getTagInfo($file);
           
            $file_info = new FileInfo();
            $file_info->setTrackNumber($info_data['track_number']);
            $file_info->setArtist($info_data['artist']);
            $file_info->setAlbum($info_data['album']);
            $file_info->setTitle($info_data['title']);
            $em->persist($file_info);
            
            $lf = new LibraryFile();
            $lf->setFileName($file->getRelativePathname());
            $lf->setUser($this->getUser());
            $lf->setInfo($file_info);
            $em->persist($lf);
            $counter++;
        }

        $em->flush();

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
