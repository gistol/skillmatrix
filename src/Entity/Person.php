<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Person\Doctrine")
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     *
     * @Groups({"withoutRatings"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @var string
     *
     * @Groups({"withoutRatings"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="person", cascade={"remove"})
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

    public function rename(string $newName): void
    {
        $this->setName($newName);
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
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRatings(): ArrayCollection
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
