<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    /**
     * @Route("/article", name="get-all-action")
     * @Method("GET")
     *
     * @return Response
     */
    public function getAllAction()
    {
        $articles = $this->get('app.repository.article')->findAll();

        return new Response($this->get('jms_serializer')->serialize($articles, 'json'), Response::HTTP_OK);
    }

    /**
     * @Route("/article/{id}", name="get-single-action", requirements={"id": "\d+"})
     * @Method("GET")
     *
     * @param $id
     * @return Response
     */
    public function getAction($id)
    {
        $article = $this->get('app.repository.article')->findById($id);

        if (!$article) {
            $this->createNotFoundException(sprintf('Article id %d does not exist', $id));
        }

        return new Response($this->get('jms_serializer')->serialize($article, 'json'), Response::HTTP_OK);
    }

    /**
     * @Route("/article", name="post-action")
     * @Method("POST")
     *
     * @param Request $request
     * @return Response
     */
    public function postAction(Request $request)
    {
        $entityManager = $this->get('doctrine.orm.default_entity_manager');

        $article = $this->get('jms_serializer')->deserialize($request->getContent(), Article::class, 'json');

        $entityManager->persist($article);
        $entityManager->flush();

        return new Response($article->getId(), Response::HTTP_CREATED);
    }

    /**
     * @Route("/article/{id}", name="delete-action", requirements={"id": "\d+"})
     * @Method("DELETE")
     *
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $entityManager = $this->get('doctrine.orm.default_entity_manager');

        $article = $this->get('app.repository.article')->findById($id);

        if (!$article) {
            $this->createNotFoundException(sprintf('Article id %d does not exist', $id));
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return new Response(sprintf('Removed id %d', $id), Response::HTTP_OK);
    }

    /**
     * @Route("/article/{id}/rate/{rating}", name="rate-action", requirements={"id": "\d+", "rating": "\d+"})
     * @Method("POST")
     *
     * @param $id
     * @param $rating
     *
     * @return Response
     */
    public function rateAction($id, $rating)
    {
        $entityManager = $this->get('doctrine.orm.default_entity_manager');

        /** @var Article $article */
        $article = $this->get('app.repository.article')->findById($id);

        if (!$article) {
            $this->createNotFoundException(sprintf('Article id %d does not exist', $id));
        }

        $article->rate($rating);

        $entityManager->persist($article);
        $entityManager->flush();

        return new Response($this->get('jms_serializer')->serialize($article, 'json'), Response::HTTP_OK);
    }

    /**
     * @Route("/article/{id}/comment", name="comment-action", requirements={"id": "\d+"})
     * @Method("POST")
     *
     * @param $request
     * @param $id
     *
     * @return Response
     */
    public function commentAction(Request $request, $id)
    {
        $entityManager = $this->get('doctrine.orm.default_entity_manager');

        /** @var Article $article */
        $article = $this->get('app.repository.article')->findById($id);

        if (!$article) {
            $this->createNotFoundException(sprintf('Article id %d does not exist', $id));
        }

        $data = $this->get('jms_serializer')->deserialize($request->getContent(), 'array', 'json');

        if (!array_key_exists('email', $data) || !array_key_exists('content', $data)) {
            throw new \InvalidArgumentException('Email and Text must be provided to add a comment');
        }

        $article->comment($data['email'], $data['content']);

        $entityManager->persist($article);
        $entityManager->flush();

        return new Response($this->get('jms_serializer')->serialize($article, 'json'), Response::HTTP_OK);
    }
}
