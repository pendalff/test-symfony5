<?php

namespace App\Repository;

use App\Entity\News;
use App\Twig\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function findAllActiveByPage(Page $page, ?int $categoryId = null)
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->andWhere('t.state = :stateActive')
            ->setParameter('stateActive', News::STATE_ACTIVE)
            ->orderBy('t.' . $page->getSortField(), $page->getSortDirection());

        if ($categoryId) {
            $queryBuilder
                ->andWhere('t.category = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        $query = $queryBuilder->getQuery();
        $query
            ->setFirstResult(abs(($page->getCurrentPage() - 1) * $page->getenteriesLimit()))
            ->setMaxResults($page->getenteriesLimit());

        $paginator = new Paginator($query, true);
        $paginator->setUseOutputWalkers(false);

        $page->setTotalEnteries($paginator->count());
        if ($page->getCurrentPage() < 1 || $page->getCurrentPage() > $page->getTotalPages()) {
            throw new \OutOfBoundsException(sprintf('%d is out of pages range: 1..%d', $page->getCurrentPage(), $page->getTotalPages()));
        }

        $paginator->setUseOutputWalkers(true);

        return $paginator;
    }

    /**
     * @param string $slug
     * @return News
     * @throws EntityNotFoundException
     */
    public function findOneActiveBySlug(string $slug): ?News
    {
        $entity = $this->findOneBy($filters = [
            'state' => News::STATE_ACTIVE,
            'slug'  => $slug
        ]);

        if (!$entity) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(News::class, $filters);
        }

        return $entity;
    }

    /**
     * @param Query $query
     * @param int $page
     * @param int $limit
     * @param bool $fetchJoinCollection
     * @return Paginator
     */
    protected function getPaginator(Query $query, int $page = 1, int $limit = 15, bool $fetchJoinCollection = true): Paginator
    {
        $query
            ->setFirstResult(abs(($page - 1) * $limit))
            ->setMaxResults($limit);

        $paginator = new Paginator($query, $fetchJoinCollection);
        $paginator->setUseOutputWalkers(false);

        $lastPage = max(ceil($paginator->count() / $limit), 1);

        if ($page < 1 || $page > $lastPage) {
            throw new \OutOfBoundsException(sprintf('%d is out of pages range: 1..%d', $page, $lastPage));
        }

        $paginator->setUseOutputWalkers(true);

        return $paginator;
    }
}
