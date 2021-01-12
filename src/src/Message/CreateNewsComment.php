<?php

namespace App\Message;

class CreateNewsComment implements MessageInterface
{
    private int $newsId;

    private ?string $text;

    public function getNewsId(): int
    {
        return $this->newsId;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setNewsId(int $newsId): self
    {
        $this->newsId = $newsId;

        return $this;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
