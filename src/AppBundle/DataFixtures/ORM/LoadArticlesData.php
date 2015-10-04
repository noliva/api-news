<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Article;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Tests\Fixtures\ContainerAwareFixture;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadArticlesData extends ContainerAwareFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');

        for ($i = 1; $i < 4; $i++) {
            $article = new Article('author@author.com', sprintf('Article Test %d', $i), sprintf('This is a test for article %d', $i));
            $entityManager->persist($article);
        }

        $entityManager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

}
