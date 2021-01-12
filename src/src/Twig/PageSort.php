<?php

namespace App\Twig;

trait PageSort
{
    private $sortDirectionAsc  = 'ASC';
    private $sortDirectionDesc = 'DESC';

    private $sortDirections = ['ASC', 'DESC'];

    protected ?string $sortDirection;

    protected ?string $sortField;

    /**
     * @return string|null
     */
    public function getSortDirection(): ?string
    {
        return $this->sortDirection;
    }

    /**
     * @param string|null $sortDirection
     * @return static
     */
    public function setSortDirection(?string $sortDirection)
    {
        if (!in_array($sortDirection, [$this->sortDirections])) {
            $sortDirection = $this->sortDirectionDesc;
        }

        $this->sortDirection = $sortDirection;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSortField(): ?string
    {
        return $this->sortField;
    }

    /**
     * @param string|null $sortField
     * @return PageSort
     */
    public function setSortField(?string $sortField)
    {
        $this->sortField = $sortField;
        return $this;
    }
}
