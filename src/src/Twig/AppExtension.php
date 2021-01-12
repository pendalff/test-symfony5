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

    public function getCategories()
    {
        return $this->cache->get('menuCategoriesTree', function (ItemInterface $item) {
            $item->expiresAfter(30);

            $categories = [];

            $rootCategories = $this->categoryRepository->findBy(['parent' => null]);
            foreach ($rootCategories as $rootCategory) {
                if ($rootCategory->hasNotEmptyChildrensOrHasActiveNews()) {
                    $categories[] = $rootCategory->toTreeArray();
                }
            }

            return $categories;
        });
    }
}