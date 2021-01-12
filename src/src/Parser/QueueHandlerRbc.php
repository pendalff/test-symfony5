<?php

namespace App\Parser;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class QueueHandlerRbc - worker for parse rbc news
 * @package App\Parser
 */
class QueueHandlerRbc implements QueueHandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private HttpClientInterface   $client;
    private MessageBusInterface   $bus;
    private ResultMapperInterface $mapper;

    public function __construct(MessageBusInterface $bus, HttpClientInterface $client, ResultMapperRbc $mapper)
    {
        $this->bus    = $bus;
        $this->client = $client;
        $this->mapper = $mapper;
    }

    /**
     * @return ResultMapperRbc|ResultMapperInterface
     */
    public function getMapper(): ResultMapperInterface
    {
        return $this->mapper;
    }

    /**
     * Get content from url, convert it to CreateNews message and dispatch to bus
     * @param QueueRbc $queueMessage
     */
    public function __invoke(QueueRbc $queueMessage)
    {
        try {
            $this->logger->info(strtr('Start parse :url', ['url' => $queueMessage->getUrl()]));

            $newsMessage = $this->mapper->mapToMessage(new ResultItem(
                $queueMessage->getUrl(),
                $this->client->request('GET', $queueMessage->getUrl())->getContent()
            ));

            $this->bus->dispatch($newsMessage);

            $this->logger->info(strtr('End parse :url', ['url' => $queueMessage->getUrl()]));
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), [$e]);
        }
    }
}
