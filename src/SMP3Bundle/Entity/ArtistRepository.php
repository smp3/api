<?php

namespace SMP3Bundle\Entity;

use Doctrine\ORM\EntityRepository;
use SMP3Bundle\Entity\User;

class ArtistRepository extends LibraryRepository {

    public function findAllByUser(User $user) {
        $query = $this->genericFindAllByUser($user, 'SMP3Bundle\Entity\Artist');
        return $query->getQuery()->getResult();
    }

}
