<?php

namespace AppBundle\Entity;

class Comment
{
    private $id;
    private $article;
    private $email;
    private $content;
    private $createdAt;

    /**
     * @param Article $article
     * @param $email
     * @param $content
     */
    public function __construct(Article $article, $email, $content)
    {
        $this->article = $article;
        $this->email = $email;
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
