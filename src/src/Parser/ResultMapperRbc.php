<?php

namespace App\Parser;

use App\Message\MessageInterface;
use App\Message\CreateNews;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\DomCrawler\Crawler;

class ResultMapperRbc implements ResultMapperInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private const DEFAULT_CATEGORY_ID = 1;

    public function mapToMessage(ResultInterface $item): MessageInterface
    {
        $this->logger->info(strtr("Map ResultItem for [:url]", [':url' => $item->getUrl()]));

        try {
            $newsMessage = new CreateNews();
            $crawler     = new Crawler($item->getHtml());

            $selectors = $this->getSelectors($item);

            $content = implode(
                PHP_EOL,
                $crawler->filter($selectors['content'])->each(function (Crawler $node, $i){
                    return $node->text();
                })
            );


            $state = true;
            try {
                $title = $crawler->filter($selectors['title'])->text();
            } catch (\InvalidArgumentException $e) {
                $title = "INVALID TITLE SELECTOR";
                $state = false;
            }

            $imageNode = $crawler->filter($selectors['image']);

            return $newsMessage
                ->setCategoryId(self::DEFAULT_CATEGORY_ID)
                ->setState($state)
                ->setTitle($title)
                ->setPreview(mb_substr($content, 0, 500))
                ->setContent($content)
                ->setImage($imageNode->count() ? $imageNode->attr('src') : null);

        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }

    protected function getSelectors(ResultInterface $item)
    {
        if (strpos($item->getUrl(), 'traffic.rbc.ru') !== false) {
            return [
                'content' => '.article__body div',
                'image'   => '.article__image--main img',
                'title'   => '.article__title',
            ];
        }

        return [
            'content' => '.article__text p',
            'image'   => '.article__main-image__image',
            'title'   => '.article__header__title h1',
        ];
    }
}