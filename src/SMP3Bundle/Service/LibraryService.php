<?php

namespace SMP3Bundle\Service;

use Symfony\Component\Finder\Finder;
use SMP3Bundle\Entity\User;
use SMP3Bundle\Entity\LibraryFile;
use SMP3Bundle\Entity\Track;
use SMP3Bundle\Entity\Album;
use SMP3Bundle\Entity\Artist;

class LibraryService {

    protected $container;
    protected $exts = ['mp3', 'mp4', 'ogg', 'm4a'];

    public function __construct($container) {
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
    }

    protected function setLibraryFile(LibraryFile $library_file, User $user, $file, $info_data, $md5) {

        $library_file->setFileName($file->getRelativePathname());
        $library_file->setMD5($md5);
        $library_file->setUser($user);
        $artist_repo = $this->em->getRepository('SMP3Bundle:Artist');
        $album_repo = $this->em->getRepository('SMP3Bundle:Album');


        $track = $library_file->getTrack(); 
        if (!$track) {
             $track = new Track();
        }

        if ($info_data) {

            $artist = $artist_repo->findOneByName($info_data['artist']);
            if (!$artist) {
                $artist = new Artist();
            }

            $album = $album_repo->findOneByTitle($info_data['album']);

            if (!$album) {
                $album = new Album();
            }


           

            $artist->setName($info_data['artist']);
            $album->setTitle($info_data['album']);

            $track->setNN('title', $info_data['title']);
            $track->setNN('number', $info_data['track_number']);



            if ($artist->getName()) {
                $this->em->persist($artist);
                $this->em->flush();
                $album->setArtist($artist);
            }

            if ($album->getTitle()) {
                $this->em->persist($album);
                $this->em->flush();
                $track->setAlbum($album);
                $library_file->setAlbum($album);
            }

            if ($track->getTitle()) {
                $this->em->persist($track);
                $library_file->setTrack($track);
                $library_file->setArtist($artist);
            }
            
        }

        $this->em->persist($library_file);
    }

    protected function removeOrphaned($user) {
        $em = $this->container->get('doctrine')->getManager();
        $repository = $em->getRepository('SMP3Bundle:LibraryFile');
        $all = $repository->findByUser($user);
        $counter = 0;

        foreach ($all as $file) {
            if (!file_exists($user->getPath() . '/' . $file->getFileName())) {
                $em->remove($file);
                $counter++;
            }
        }

        $em->flush();

        return $counter;
    }

    public function discover(User $user) {
        $info_service = $this->container->get('FileInfoService');
        $finder = new Finder();
        $finder->files()->in($user->getPath());
        $em = $this->container->get('doctrine')->getManager();
        $repository = $em->getRepository('SMP3Bundle:LibraryFile');

        $this->removeOrphaned($user);

        $counter = 0;
        foreach ($finder as $file) {
            if (!in_array($file->getExtension(), $this->exts)) {
                continue;
            }

            $info_data = $info_service->getTagInfo($file);

            $contents = file_get_contents($user->getPath() . '/' . $file->getRelativePathname());
            $md5 = md5($contents);

            $lf = $repository->findOneBy(array('md5' => $md5));

            if (!$lf) {
                $lf = new LibraryFile();
            }



            $this->setLibraryFile($lf, $user, $file, $info_data, $md5);
            $counter++;
        }

        $em->flush();

        return $counter;
    }

    public function clear(User $user) {
        $em = $this->container->get('doctrine')->getManager();
        $repository = $em->getRepository('SMP3Bundle:LibraryFile');
        $all = $repository->findByUser($user);
        foreach ($all as $file) {
            $em->remove($file);
        }

        $em->flush();
    }

}
