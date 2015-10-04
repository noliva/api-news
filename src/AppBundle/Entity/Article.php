<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Article
{
    private $id;
    private $title;
    private $slug;
    private $content;
    private $authorEmail;
    private $comments;
    private $rating = 0;
    private $votes;
    private $createdAt;

    public function __construct($authorEmail, $title, $content)
    {
        $this->authorEmail = $authorEmail;
        $this->title = $title;
        $this->content = $content;
        $this->comments = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $rating integer
     *
     * @return $this
     */
    public function rate($rating)
    {
        if ($rating < 0 || $rating > 5) {
            throw new \InvalidArgumentException('The rate must be between 0 and 5');
        }

        if ($this->votes == 0) {
            $this->rating = $rating;
        } else {
            $this->rating = $this->rating + $rating / 2;
        }

        $this->votes++;

        return $this;
    }

    /**
     * @param $email
     * @param $content
     *
     * @return $this
     */
    public function comment($email, $content)
    {
        if (!is_string($email)) {
            throw new \InvalidArgumentException('Email must be type string');
        }

        if (!is_string($content)) {
            throw new \InvalidArgumentException('Content must be type string');
        }

        $comment = new Comment($this, $email, $content);

        $this->comments->add($comment);

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }
}
