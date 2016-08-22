<?php

namespace SMP3Bundle\Service;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
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

    public function __construct($em, $fileInfo, $configService, $checksumService)
    {

        $this->em = $em->getManager();
        $this->fileInfo = $fileInfo;
        $this->configService = $configService;
        $this->checksumService = $checksumService;
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

        $repository = $this->em->getRepository('SMP3Bundle:LibraryFile');
        $all = $repository->findByUser($user);
        $counter = 0;

        foreach ($all as $file) {
            if (!file_exists($user->getPath() . '/' . $file->getFileName())) {
                $this->em->remove($file);
                ++$counter;
            }
        }

        $this->em->flush();

        return $counter;
    }

    protected function debug_msg($msg)
    {
        if ($this->debug) {
            echo $msg . "\n";
        }
    }

    public function discover(User $user, $subDir = null)
    {
        $return = new \stdClass();
        $stime = microtime(true);

        $path = $user->getPath();

        if ($subDir != null) {
            $path .= '/' . $subDir;
        }

        $finder = new Finder();
        $finder->files()->in($path);
        $repository = $this->em->getRepository('SMP3Bundle:LibraryFile');

        $this->removeOrphaned($user);

        $counter = 0;
        foreach ($finder as $file) {

            $filePath = $path . '/' . $file->getRelativePathname();
            $this->debug_msg('Processing: ' . $filePath);

            if (!in_array($file->getExtension(), $this->configService->getDiscoverableExts())) {
                continue;
            }

            $checksum = $this->checksumService->fileChecksum($filePath);
            $lf = $repository->findOneBy(array('checksum' => $checksum));

            if (!$lf) {
                $lf = new LibraryFile();
                /*
                 * Checksum is the same so the info data is the same
                 */
                $info_data = $this->fileInfo->getTagInfo($file);
            } else {
                $info_data = null;
            }

            $this->setLibraryFile($lf, $user, $file, $info_data, $checksum);
            ++$counter;
        }

        $this->em->flush();
        $etime = microtime(true);
        $return->time = $etime - $stime;
        $return->counter = $counter;

        return $return;
    }

    public function addLibraryFile(User $user, $filePath, $fileExtension = null, $noChecksumCheck = false)
    {
        $repository = $this->em->getRepository('SMP3Bundle:LibraryFile');
        
        if ($fileExtension && !in_array($fileExtension, $this->configService->getDiscoverableExts())) {
            return;
        }

        $checksum = $this->checksumService->fileChecksum($filePath);
        
        if ($noChecksumCheck) {
            $lf = null;
        } else {
            $lf = $repository->findOneBy(array('checksum' => $checksum));
        }

        if (!$lf) {
            $lf = new LibraryFile();
            /*
             * Checksum is the same so the info data is the same
             */
            $info_data = $this->fileInfo->getTagInfo($filePath);
        } else {
            $info_data = null;
        }

        $fileDir = dirname($filePath);
        $fileName = str_replace($fileDir, "", $filePath);
        $this->setLibraryFile($lf, $user, new SplFileInfo($fileName, $fileDir, $filePath), $info_data, $checksum);
    }

    public function clear()
    {
        $repository = $this->em->getRepository('SMP3Bundle:LibraryFile');
        $artist_repository = $this->em->getRepository('SMP3Bundle:Artist');
        $album_repository = $this->em->getRepository('SMP3Bundle:Album');

        $all = $repository->findAll();
        $all_artists = $artist_repository->findAll();
        $all_albums = $album_repository->findAll();

        foreach ($all as $file) {
            $this->em->remove($file);
        }

        foreach ($all_albums as $album) {
            $this->em->remove($album);
        }

        foreach ($all_artists as $artist) {
            $this->em->remove($artist);
        }

        $this->em->flush();
    }
}
