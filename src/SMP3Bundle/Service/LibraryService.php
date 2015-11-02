<?php

namespace SMP3Bundle\Service;

use Symfony\Component\Finder\Finder;
use SMP3Bundle\Entity\User;
use SMP3Bundle\Entity\LibraryFile;
use SMP3Bundle\Entity\FileInfo;

class LibraryService {

    protected $container;
    protected $exts = ['mp3', 'mp4', 'ogg', 'm4a'];

    public function __construct($container) {
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
    }

    protected function setLibraryFile(LibraryFile $library_file, FileInfo $file_info, User $user, $file, $info_data, $md5) {

        $library_file->setFileName($file->getRelativePathname());
        $library_file->setMD5($md5);
        $library_file->setUser($user);

        if ($info_data) {
            $file_info->setTrackNumber($info_data['track_number']);
            $file_info->setArtist($info_data['artist']);
            $file_info->setAlbum($info_data['album']);
            $file_info->setTitle($info_data['title']);
            $this->em->persist($file_info);
            $library_file->setInfo($file_info);
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

            if ($lf) {
                $file_info = $lf->getInfo();
            } else {
                $file_info = new FileInfo();
                $lf = new LibraryFile();
            }

            $this->setLibraryFile($lf, $file_info, $user, $file, $info_data, $md5);
            $counter++;
        }

        $em->flush();

        return $counter;
    }

}
