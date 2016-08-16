<?php

namespace SMP3Bundle\Entity;

class ArtistRepository extends LibraryRepository
{
    public function findAllByUser(User $user, $max=0, $from=0)
    {
        $query = $this
            ->genericFindAllByUser($user, 'SMP3Bundle\Entity\Artist')
            ->setMaxResults($max)
            ->setFirstResult($from)
            ;
        
        return $query->getQuery()->getResult();
    }
}
