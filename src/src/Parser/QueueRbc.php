<?php

namespace App\Parser;

class QueueRbc implements QueueInterface
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string Target URL for parse
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}