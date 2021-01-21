<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use App\Twig\AppRuntime;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public const MENU_ITEMS_CACHE_KEY = 'menuCategoriesTree';

    private CacheInterface $cache;
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository, CacheInterface $cache)
    {
        $this->categoryRepository = $categoryRepository;
        $this->cache = $cache;
    }

    /**
     * @inheritDoc
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getCategories', [$this, 'getCategories']),
        ];
    }

    public function getCategories(): array
    {
        return $this->cache->get(self::MENU_ITEMS_CACHE_KEY, function (ItemInterface $item) {
            $item->expiresAfter(30);

            return $this->categoryRepository->getTreeArray();
        });
    }

    public function clearCategoriesCache(): bool
    {
        return $this->cache->delete(self::MENU_ITEMS_CACHE_KEY);
    }
}