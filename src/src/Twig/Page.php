<?php

namespace App\Twig;

use Traversable;

/**
 * Class Page
 * @package App\Twig
 */
class Page
{
    use PageSort;

    protected int $currentPage     = 1;
    protected int $enteriesLimit = 3;
    protected int $totalEnteries   = 0;
    protected ?Traversable  $enteries;

    public static function factory(array $arguments = []): self
    {
        $self = new static();
        foreach ($arguments as $argKey => $argValue) {
            $setMethod = 'set' . ucfirst($argKey);
            if (method_exists($self, $setMethod)) {
                $self->$setMethod($argValue);
            }
        }

        return $self;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @param int $page
     * @return Page
     */
    public function setCurrentPage(int $currentPage): Page
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * @return int
     */
    public function getEnteriesLimit(): int
    {
        return $this->enteriesLimit;
    }

    /**
     * @param int $enteriesLimit
     * @return Page
     */
    public function setEnteriesLimit(int $enteriesLimit): Page
    {
        $this->enteriesLimit = $enteriesLimit;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalEnteries(): int
    {
        return $this->totalEnteries;
    }

    /**
     * @param int $totalEnteries
     * @return Page
     */
    public function setTotalEnteries(int $totalEnteries): Page
    {
        $this->totalEnteries = $totalEnteries;
        return $this;
    }

    /**
     * @return \Traversable
     */
    public function getEnteries(): \Traversable
    {
        return $this->enteries;
    }

    /**
     * @param \Traversable  $enteries
     * @return Page
     */
    public function setEnteries(\Traversable $enteries): Page
    {
        $this->enteries = $enteries;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalPages(): int
    {
        return max(ceil($this->totalEnteries / $this->enteriesLimit), 1);
    }
}
