<?php

namespace App\Message;

class CreateNewsImage implements MessageInterface
{
    private int    $newsId;

    private string $imageUrl;

    public function __construct(int $newsId, string $imageUrl)
    {
        $this->newsId   = $newsId;
        $this->imageUrl = $imageUrl;
    }
    
    public function getNewsId(): int
    {
        return $this->newsId;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}