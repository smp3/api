<?php

namespace SMP3Bundle\Entity;

use Doctrine\ORM\EntityRepository;
use SMP3Bundle\Entity\User;


class AlbumRepository extends LibraryRepository {

    public function findAllByUser(User $user, $artist = null) {
        $query = $this->genericFindAllByUser($user, 'SMP3Bundle\Entity\Album');
        if ($artist) {
            $query->andWhere('a.artist=:artist')->setParameter('artist', $artist);
        }
        return $query->getQuery()->getResult();
    }

}
