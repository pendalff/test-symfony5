<?php

namespace App\Parser;

use App\Message\MessageInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

interface QueueHandlerInterface extends MessageHandlerInterface
{
    public function getMapper(): ResultMapperInterface;
}