<?php

namespace SMP3Bundle\Entity;

class ArtistRepository extends LibraryRepository
{
    public function findAllByUser(User $user)
    {
        $query = $this->genericFindAllByUser($user, 'SMP3Bundle\Entity\Artist');

        return $query->getQuery()->getResult();
    }
}
