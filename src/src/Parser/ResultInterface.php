<?php

namespace App\Parser;

/**
 * Interface ParserResultInterface
 * @package App\Parser
 */
interface ResultInterface
{
    public function getUrl(): string;

    public function getHtml(): string;
}
