<?php

namespace SMP3Bundle\Entity;

use Doctrine\ORM\EntityRepository;

class LibraryRepository extends EntityRepository
{
    public function genericFindAllByUser(User $user, $join_entity)
    {
        $query = $this->getEntityManager()
                ->createQueryBuilder()
                ->select('a')
                ->from('SMP3Bundle\Entity\LibraryFile', 'lf')
                ->innerJoin($join_entity, 'a')
                ->groupBy('a')
                ->where('lf.user=:user')
                ->setParameter('user', $user)
        ;

        return $query;
    }
}
