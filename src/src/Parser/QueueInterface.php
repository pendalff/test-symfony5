<?php

namespace App\Parser;

use App\Message\MessageInterface;

interface QueueInterface extends MessageInterface
{
    /**
     * @return string Target URL for parse
     */
    public function getUrl(): string;
}