<?php

namespace SMP3Bundle\Service;

use Symfony\Component\Finder\Finder;
use SMP3Bundle\Entity\User;
use SMP3Bundle\Entity\LibraryFile;
use SMP3Bundle\Entity\Track;
use SMP3Bundle\Entity\Album;
use SMP3Bundle\Entity\Artist;

class LibraryService
{
    protected $container,
            $exts = ['mp3', 'mp4', 'ogg', 'm4a'],
            $debug = false

    ;

    public function __construct($container)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    protected function setLibraryFile(LibraryFile $library_file, User $user, $file, $info_data, $md5)
    {
        $library_file->setFileName($file->getRelativePathname());
        $library_file->setChecksum($md5);
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
                if ($artist->getName()) {
                    $library_file->setArtist($artist);
                }
            }
        }

        $this->em->persist($library_file);
    }

    protected function removeOrphaned($user)
    {
        $em = $this->container->get('doctrine')->getManager();
        $repository = $em->getRepository('SMP3Bundle:LibraryFile');
        $all = $repository->findByUser($user);
        $counter = 0;

        foreach ($all as $file) {
            if (!file_exists($user->getPath().'/'.$file->getFileName())) {
                $em->remove($file);
                ++$counter;
            }
        }

        $em->flush();

        return $counter;
    }

    protected function debug_msg($msg)
    {
        if ($this->debug) {
            echo $msg."\n";
        }
    }

    public function discover(User $user)
    {
        $return = new \stdClass();
        $stime = microtime(true);
        $info_service = $this->container->get('smp3.fileinfo');
        $finder = new Finder();
        $finder->files()->in($user->getPath());
        $em = $this->container->get('doctrine')->getManager();
        $repository = $em->getRepository('SMP3Bundle:LibraryFile');

        $this->removeOrphaned($user);

        $counter = 0;
        foreach ($finder as $file) {
            $this->debug_msg('Processing: '.$user->getPath().'/'.$file->getRelativePathname());
            if (!in_array($file->getExtension(), $this->exts)) {
                continue;
            }

            $contents = file_get_contents($user->getPath().'/'.$file->getRelativePathname());
            $checksum = crc32($contents);

            $lf = $repository->findOneBy(array('checksum' => $checksum));

            if (!$lf) {
                $lf = new LibraryFile();
                /*
                 * Checksum is the same so the info data is the same
                 */
                $info_data = $info_service->getTagInfo($file);
            } else {
                $info_data = null;
            }

            $this->setLibraryFile($lf, $user, $file, $info_data, $checksum);
            ++$counter;
        }

        $em->flush();
        $etime = microtime(true);
        $return->time = $etime - $stime;
        $return->counter = $counter;

        return $return;
    }

    public function clear()
    {
        $em = $this->container->get('doctrine')->getManager();
        $repository = $em->getRepository('SMP3Bundle:LibraryFile');
        $artist_repository = $em->getRepository('SMP3Bundle:Artist');
        $album_repository = $em->getRepository('SMP3Bundle:Album');

        $all = $repository->findAll();
        $all_artists = $artist_repository->findAll();
        $all_albums = $album_repository->findAll();

        foreach ($all as $file) {
            $em->remove($file);
        }

        foreach ($all_albums as $album) {
            $em->remove($album);
        }

        foreach ($all_artists as $artist) {
            $em->remove($artist);
        }

        $em->flush();
    }
}
