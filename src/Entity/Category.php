<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=Tricks::class, mappedBy="category", cascade={"persist", "remove"})
     */
    private $category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?Tricks
    {
        return $this->category;
    }

    public function setCategory(Tricks $category): self
    {
        $this->category = $category;

        // set the owning side of the relation if necessary
        if ($category->getCategory() !== $this) {
            $category->setCategory($this);
        }

        return $this;
    }



    public function __toString()
    {

        if(is_null($this->category)) {
            return 'NULL';

        }
        return $this->category;


    }




}
