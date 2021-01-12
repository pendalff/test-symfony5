<?php

namespace App\Parser;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Message\CreateNews;

/**
 * Class ParserRbc - create queue messages with parsing tasks
 * @package App\Parser
 */
class ParserRbc implements ParserInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private const targetUrl = 'https://www.rbc.ru';

    private HttpClientInterface $client;

    private MessageBusInterface $bus;

    /**
     * ParserRbc constructor.
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client, MessageBusInterface $messageBus)
    {
        $this->client = $client;
        $this->bus = $messageBus;
    }

    public function parse(): bool
    {
        $status = false;

        try {
            $response = $this->client->request('GET', self::targetUrl);

            $crawler = new Crawler($response->getContent());
            $crawler
                ->filter('.js-news-feed-list .news-feed__item')
                ->each(function (Crawler $node, $i) {
                    $this->bus->dispatch(new QueueRbc($node->attr('href')));
                });

            $status = true;
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), [$e]);

            $status = false;
        }

        return $status;
    }
}
