<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RatingRepository")
 */
class Rating
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
     * @var string
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

    public function setReviewer(?Reviewer $reviewer): self
    {
        $this->reviewer = $reviewer;

        return $this;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }
}
