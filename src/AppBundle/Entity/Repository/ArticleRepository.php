<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function findById($id)
    {
        return $this->findOneBy(['id' => $id]);
    }
}
