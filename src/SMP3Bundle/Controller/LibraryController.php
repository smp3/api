<?php

namespace SMP3Bundle\Controller;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\MimeType\FileBinaryMimeTypeGuesser;
use SMP3Bundle\Entity\LibraryFile;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * @RouteResource("library")
 */
class LibraryController extends APIBaseController implements ClassResourceInterface
{

    protected $user, $artistRepository, $albumRepository, $libraryRepository;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->artistRepository = $this->em->getRepository('SMP3Bundle:Artist');
        $this->albumRepository = $this->em->getRepository('SMP3Bundle:Album');
        $this->libraryRepository = $this->em->getRepository('SMP3Bundle:LibraryFile');
    }

    public function getArtistsAction(Request $request)
    {
        
        $pagingInfo = $this->getPagingInfo($request);
        
        $artists =  $this->artistRepository->findAllByUser($this->getUser(), $pagingInfo['max'], $pagingInfo['from']);
        
        return $this->handleView($this->view($artists));
    }

    public function getAlbumsAction(Request $request)
    {
        $pagingInfo = $this->getPagingInfo($request);
        
        $albums = $this->albumRepository
            ->findAllByUser($this->getUser(), $this->artistRepository->findByName($request->get('artist')), 
                $pagingInfo['max'], $pagingInfo['from']);

        return $this->handleView($this->view($albums));
    }

    public function getAction(Request $request)
    {
        
        $pagingInfo = $this->getPagingInfo($request);
        
        $findby = ['user' => $this->getUser()];

        if ($request->get('artist')) {
            $findby['artist'] = $this->artistRepository->findByName($request->get('artist'));
        }

        if ($request->get('album')) {
            $findby['album'] = $this->albumRepository->findByTitle($request->get('album'));
        }

        $files = $this->libraryRepository->findBy($findby, [], $pagingInfo['max'], $pagingInfo['from']);


        $view = View::create();
        $view->setData($files);
        $view->setSerializationContext(SerializationContext::create()->setGroups(['library']));

        return $this->handleView($view);
    }

    public function getDiscoverAction()
    {

        $msg = [
            'user_id' => $this->getUser()->getId(),
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
        $file_name = $file->getUser()->getPath() . '/' . $file->getFileName();
        $file_contents = new File($file_name);

        $response = new BinaryFileResponse($file_contents);
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}
