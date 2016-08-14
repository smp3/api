<?php

namespace SMP3Bundle\Controller;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\MimeType\FileBinaryMimeTypeGuesser;
use SMP3Bundle\Entity\LibraryFile;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @RouteResource("library")
 */
class LibraryController extends APIBaseController implements ClassResourceInterface
{
    protected $user, $artist_repository, $album_repository, $library_repository;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->artist_repository = $this->em->getRepository('SMP3Bundle:Artist');
        $this->album_repository = $this->em->getRepository('SMP3Bundle:Album');
        $this->library_repository = $this->em->getRepository('SMP3Bundle:LibraryFile');
    }

    public function getArtistsAction()
    {
        $repository = $this->em->getRepository('SMP3Bundle:Artist');

        return $this->handleView($this->view($repository->findAllByUser($this->getUser())));
    }

    public function getAlbumsAction(Request $request)
    {
        $albums = $this->album_repository
            ->findAllByUser($this->getUser(), $this->artist_repository->findByName($request->get('artist')));

        return $this->handleView($this->view($albums));
    }

    public function getTree()
    {
        $artists = $this->artist_repository->findAllByUser($this->getUser());

        $albums = $this->album_repository->findAllByUser($this->getUser());
        $library = $this->library_repository->findByUser($this->getUser());

        foreach ($library as $lib_item) {
        }
    }

    public function getAction(Request $request)
    {
        /// $context = new SerializationContext();
        $findby = ['user' => $this->getUser()];

        if ($request->get('artist')) {
            $findby['artist'] = $this->artist_repository->findByName($request->get('artist'));
        }

        if ($request->get('album')) {
            $findby['album'] = $this->album_repository->findByTitle($request->get('album'));
        }

        $files = $this->library_repository->findBy($findby);
        $this->get('smp3.fileinfo')->addTrackTitles($files);
        $view = View::create();
        $view->setData($files);

        return $this->handleView($view);
    }

    public function getDiscoverAction()
    {   
        
        $msg = [
         'user_id'=>$this->getUser()->getId(),   
        ];
        
        $this->get('old_sound_rabbit_mq.discover_producer')->publish(json_encode($msg));
        return $this->handleView($this->view(0, 200));
    }

    /**
     * @Get("/stream/{file_id}")
     */
    public function getStreamAction($file_id)
    {
        $file = $this->em->getRepository('SMP3Bundle:LibraryFile')->findOneBy(array('id' => $file_id));
        $file_name = $file->getUser()->getPath().'/'.$file->getFileName();
        $file_contents = new File($file_name);

//        $mime_guess = new FileBinaryMimeTypeGuesser(); 
//        $mime = $mime_guess->guess($file_name);

        $response = new BinaryFileResponse($file_contents);
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}
