<?php

namespace App\Parser;

use Symfony\Component\Messenger\MessageBusInterface;

class ParserProcessor
{
    private MessageBusInterface $bus;
    private $parsers = [];

    public function __construct(ParserRbc $parser)
    {
        $this->parsers[] = $parser;
    }

    public function exec(): bool
    {
        foreach ($this->parsers as $parser) {
            $parser->parse();
        }

        return true;
    }
}