<?php

namespace App\Parser;

class ResultItem implements ResultInterface
{
    private string $url;

    private string $html;

    public function __construct(string $url, string $html)
    {
        $this->url  = $url;
        $this->html = $html;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getHtml(): string
    {
        return $this->html;
    }
}
