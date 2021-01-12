<?php

namespace App\Message;

class CreateNews implements MessageInterface
{
    private int     $categoryId;
    private bool    $state;
    private string  $title;
    private string  $preview;
    private string  $content;
    private ?string $image;

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     * @return CreateNews
     */
    public function setCategoryId(int $categoryId): CreateNews
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    /**
     * @return bool
     */
    public function getState(): bool
    {
        return $this->state;
    }

    /**
     * @param bool $state
     * @return CreateNews
     */
    public function setState(bool $state): CreateNews
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return CreateNews
     */
    public function setTitle(string $title): CreateNews
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getPreview(): string
    {
        return $this->preview;
    }

    /**
     * @param string $preview
     * @return CreateNews
     */
    public function setPreview(string $preview): CreateNews
    {
        $this->preview = $preview;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return CreateNews
     */
    public function setContent(string $content): CreateNews
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     * @return CreateNews
     */
    public function setImage(?string $image): CreateNews
    {
        $this->image = $image;
        return $this;
    }
}
