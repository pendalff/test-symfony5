<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(name="categories")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="childrens")
     */
    private ?Category $parent;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="parent")
     */
    private $childrens;

    /**
     * @var News[]|Collection
     * @ORM\OneToMany(targetEntity=News::class, mappedBy="category")
     */
    private $news;


    public function __construct()
    {
        $this->childrens = new ArrayCollection();
        $this->news      = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildrens(): Collection
    {
        return $this->childrens;
    }

    public function addChildren(self $children): self
    {
        if (!$this->childrens->contains($children)) {
            $this->childrens->add($children);
            $children->setParent($this);
        }

        return $this;
    }

    public function removeChildren(self $children): self
    {
        if ($this->childrens->removeElement($children)) {
            // set the owning side to null (unless already changed)
            if ($children->getParent() === $this) {
                $children->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return News[]|Collection
     */
    public function getNews()
    {
        return $this->news;
    }

    public function addNews(News $entity): self
    {
        if (!$this->news->contains($entity)) {
            $this->news->add($entity);
            $entity->setCategory($this);
        }

        return $this;
    }

    public function removeNews(News $entity): self
    {
        if ($this->news->removeElement($entity)) {
            $entity->setCategory(null);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildrensWithActiveNews(): Collection
    {
        $childrens = new ArrayCollection();

        foreach ($this->getChildrens() as $children) {
            if ($children->hasNotEmptyChildrensOrHasActiveNews()) {
                $childrens->add($children);
            }
        }

        return $childrens;
    }

    public function hasNotEmptyChildrensOrHasActiveNews(): bool
    {
        return !$this->getChildrensWithActiveNews()->isEmpty() || $this->hasActiveNews();
    }

    public function hasActiveNews(): bool
    {
        return $this->getNews()->exists(function ($key, News $element) {
            return $element->getState() === true;
        });
    }

    /**
     * Used as label for easy admin
     * @return string
     */
    public function __toString(): string
    {
        return $this->getId() . ' | ' . $this->getName();
    }

    /**
     * @return array
     */
    public function toTreeArray()
    {
        return [
            'id'        => $this->getId(),
            'name'      => $this->getName(),
            'childrens' => $this->getChildrensWithActiveNews()
                ->map(function (Category $item) {
                    return $item->toTreeArray();
                })
                ->toArray()
        ];
    }
}
