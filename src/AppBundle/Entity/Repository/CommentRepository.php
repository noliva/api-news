<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function findLastDayComments()
    {
        $queryBuilder = $this->createQueryBuilder('comment');

        $queryBuilder
            ->where('comment.createdAt <= :date')
            ->setParameter('date', new \DateTime('- 1 day'));

        return $queryBuilder->getQuery()->getResult();
    }
}
