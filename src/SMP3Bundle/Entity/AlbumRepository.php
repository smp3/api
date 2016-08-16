<?php

namespace SMP3Bundle\Entity;

class AlbumRepository extends LibraryRepository
{

    public function findAllByUser(User $user, $artist = null, $max = 0, $from = 0)
    {
        $query = $this->genericFindAllByUser($user, 'SMP3Bundle\Entity\Album');
        if ($artist) {
            $query->andWhere('a.artist=:artist')->setParameter('artist', $artist);
        }
        
        $query->setMaxResults($max)->setFirstResult($from);

        return $query->getQuery()->getResult();
    }
}
