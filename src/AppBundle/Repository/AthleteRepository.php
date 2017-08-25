<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AthleteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AthleteRepository extends EntityRepository
{
    public function deleteById($id)
    {
        $this->createQueryBuilder('a')
            ->delete()
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }
}
