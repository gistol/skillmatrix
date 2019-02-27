<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="person")
     *
     * @var \App\Entity\Rating[]
     */
    private $ratings;

    public function __construct(string $name)
    {
        $this->ratings = new ArrayCollection();
        $this->setName($name);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    private function setName(string $name): void
    {
        if (strlen($name) === 0) {
            throw new \InvalidArgumentException('Name can not be empty.');
        }

        if (strlen($name) > 50) {
            throw new \InvalidArgumentException('Name is too long, max. 50 chars is allowed.');
        }

        $this->name = $name;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\Rating[]
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setPerson($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->contains($rating)) {
            $this->ratings->removeElement($rating);

            // Set the owning side to null (unless already changed)
            if ($rating->getPerson() === $this) {
                $rating->setPerson(null);
            }
        }

        return $this;
    }
}
