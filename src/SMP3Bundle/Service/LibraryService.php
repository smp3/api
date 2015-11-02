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

    protected function setLibraryFile(LibraryFile $library_file, FileInfo $file_info, User $user, $file, $info_data) {

        $library_file->setFileName($file->getRelativePathname());
        $library_file->setUser($user);

        if ($info_data) {
            $file_info = new FileInfo();
            $file_info->setTrackNumber($info_data['track_number']);
            $file_info->setArtist($info_data['artist']);
            $file_info->setAlbum($info_data['album']);
            $file_info->setTitle($info_data['title']);
            $this->em->persist($file_info);
            $library_file->setInfo($file_info);
        }

        $this->em->persist($library_file);
    }

    public function discover(User $user) {
        $info_service = $this->container->get('FileInfoService');
        $finder = new Finder();
        $finder->files()->in($user->getPath());
        $em = $this->container->get('doctrine')->getManager();
        $all = $em->getRepository('SMP3Bundle:LibraryFile')->findByUser($user);

        if (count($all)) {
            foreach ($all as $entity) {
                $em->remove($entity);
            }
            $em->flush();
        }

        $counter = 0;
        foreach ($finder as $file) {
            if (!in_array($file->getExtension(), $this->exts)) {
                continue;
            }

            $info_data = $info_service->getTagInfo($file);

            $lf = new LibraryFile();

            if ($info_data) {
                $file_info = new FileInfo();
                $file_info->setTrackNumber($info_data['track_number']);
                $file_info->setArtist($info_data['artist']);
                $file_info->setAlbum($info_data['album']);
                $file_info->setTitle($info_data['title']);
                $em->persist($file_info);
                $lf->setInfo($file_info);
            }

            $contents = $file->getRelativePathname();
            $lf->setFileName($file->getRelativePathname());
            $lf->setUser($user);
            $lf->setMD5(md5($contents));
            
            $em->persist($lf);
            $counter++;
        }

        $em->flush();

        return $counter;
    }

}
