<?php

namespace App\Message\Handler;

use App\Entity\Category;
use App\Entity\News;
use App\Message\CreateNewsImage;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Naive and hardcode realization image upload from external source
 */
class CreateNewsImageHandler implements MessageHandlerInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    private const targetDir = '/var/www/public';

    private const webDir = '/images/upload';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(CreateNewsImage $message)
    {
        if ($imageUrl = $this->uploadImage($message->getImageUrl())) {
            /** @var News $entity */
            $entity = $this->entityManager->getReference(
                News::class,
                $message->getNewsId()
            );
            $entity->setImage($imageUrl);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }
    }

    protected function uploadImage(string $fromUrl): ?string
    {
        $webDir =  self::webDir . '/' . rand(0, 10) . '/';

        $targetDir = self::targetDir . $webDir;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $targetImage = sha1($fromUrl . microtime(true)) . '.jpg';

        return file_put_contents($targetDir . $targetImage, file_get_contents($fromUrl)) ?
            $webDir . $targetImage
            :
            null;
    }
}
