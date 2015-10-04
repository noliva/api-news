<?php

namespace AppBundle\Command;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Repository\CommentRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotifyAuthorCommand extends Command
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(CommentRepository $commentRepository, \Swift_Mailer $mailer)
    {
        parent::__construct();

        $this->commentRepository = $commentRepository;
        $this->mailer = $mailer;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('notify:author')
            ->setDescription('Notifies author of existing comments for the last 24hours')
        ;
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $comments = $this->commentRepository->findLastDayComments();

        if (!$comments) {
            $output->writeln('No new notifications');

            return;
        }

        /** @var Comment $comment */
        foreach ($comments as $comment) {
            $email = $comment->getArticle()->getAuthorEmail();

            $message = \Swift_Message::newInstance();
            $message->setSubject('You have new Notifications');
            $message->setFrom('testing@gmail.com');
            $message->setTo($email);
            $message->setBody($comment->getContent());

            $this->mailer->send($message);

            $output->writeln(
                sprintf('Email sent to %s with id %d', $email, $comment->getId())
            );
        }
    }
}
