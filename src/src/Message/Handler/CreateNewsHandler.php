<?php

namespace App\Message\Handler;

use App\Entity\Category;
use App\Entity\News;
use App\Message\CreateNews;
use App\Message\CreateNewsImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateNewsHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;

    private MessageBusInterface $messageBus;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus)
    {
        $this->entityManager = $entityManager;
        $this->messageBus    = $bus;
    }

    public function __invoke(CreateNews $message)
    {
        /** @var Category $category */
        $category = $this->entityManager->getReference(
            Category::class,
            $message->getCategoryId()
        );

        $news = new News();
        $news
            ->setState($message->getState())
            ->setCategory($category)
            ->setTitle($message->getTitle())
            ->setPreview($message->getPreview())
            ->setContent($this->convertNewLinesToParagrafs($message->getContent()));

        $this->entityManager->persist($news);
        $this->entityManager->flush();

        if ($message->getImage()) {
            $this->messageBus->dispatch(
                new CreateNewsImage($news->getId(), $message->getImage())
            );
        }
    }

    protected function convertNewLinesToParagrafs(string $string): string
    {
        return "<p>" . implode("</p><p>", explode(PHP_EOL, $string)) . "</p>";
    }
}
