<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Rating\Doctrine")
 */
class Rating implements \JsonSerializable
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
     * @ORM\Column(type="smallint")
     *
     * @var int
     */
    private $score;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var ?string
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reviewer")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var \App\Entity\Reviewer
     */
    private $reviewer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Skill")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var \App\Entity\Skill
     */
    private $skill;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="ratings")
     *
     * @var \App\Entity\Person
     */
    private $person;

    public function __construct(Person $person, Reviewer $reviewer, Skill $skill, int $score)
    {
        // TODO: add business rules
        $this->person = $person;
        $this->reviewer = $reviewer;
        $this->skill = $skill;
        $this->setScore($score);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getReviewer(): ?Reviewer
    {
        return $this->reviewer;
    }

    public function setReviewer(Reviewer $reviewer): self
    {
        $this->reviewer = $reviewer;

        return $this;
    }

    public function getSkill(): Skill
    {
        return $this->skill;
    }

    public function getPerson(): Person
    {
        return $this->person;
    }

    /**
     * Rating je povazovany za rovnaky ak je to rating pre rovnaku Person a rovnaky Skill
     *
     * @param \App\Entity\Rating $rating
     *
     * @return bool
     */
    public function isEqual(Rating $rating): bool
    {
        return $this->forSomePerson($rating->getPerson()) && $this->forSomeSkill($rating->getSkill());
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'person' => $this->getPerson(),
            'skill' => $this->getSkill(),
            'reviewer' => $this->getReviewer(),
            'score' => $this->getScore(),
            'note' => $this->getNote(),
        ];
    }

    /**
     * Testuje ci je Rating pre rovnaku Person $person
     * TODO: co ked v case volania funkcie nema Person este priradene ID (nieje persistovana)?!
     *
     * @param \App\Entity\Person $person
     *
     * @return bool
     */
    private function forSomePerson(Person $person): bool
    {
        return $this->getPerson()->getId() === $person->getId();
    }

    /**
     * Testuje ci je Rating pre rovnaky Skill $skill
     * TODO: co ked v case volania funkcie nema Skill este priradene ID (nieje persistovany)?!
     *
     * @param \App\Entity\Skill $skill
     *
     * @return bool
     */
    private function forSomeSkill(Skill $skill): bool
    {
        return $this->getSkill()->getId() === $skill->getId();
    }
}
