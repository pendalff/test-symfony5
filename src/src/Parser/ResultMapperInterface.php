<?php

namespace App\Parser;

use App\Message\MessageInterface;

interface ResultMapperInterface
{
    /**
     * @param ResultInterface $result
     * @return MessageInterface
     */
    public function mapToMessage(ResultInterface $resultItem): MessageInterface;
}