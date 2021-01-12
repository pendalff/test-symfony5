<?php

namespace App\Message\Handler;

use App\Entity\Comment;
use App\Entity\News;
use App\Message\CreateNewsComment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateNewsCommentHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(CreateNewsComment $message)
    {
        /** @var News $news */
        $news = $this->entityManager->getReference(
            News::class,
            $message->getNewsId()
        );

        $comment = new Comment();
        $comment
            ->setNews($news)
            ->setText($message->getText());

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

    }
}
