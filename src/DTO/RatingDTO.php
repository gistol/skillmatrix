<?php declare(strict_types=1);

namespace App\DTO;

final class RatingDTO
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $score;

    /**
     * @var string
     */
    private $note;

    /**
     * @var int
     */
    private $personId;

    /**
     * @var int
     */
    private $reviewerId;

    /**
     * @var int
     */
    private $skillId;

    public function __construct(?int $id, int $personId, int $reviewerId, int $skillId, int $score, ?string $note)
    {
        $this->id = $id;
        $this->personId = $personId;
        $this->reviewerId = $reviewerId;
        $this->skillId = $skillId;
        $this->score = $score;
        $this->note = $note;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function getPersonId(): int
    {
        return $this->personId;
    }

    public function getReviewerId(): int
    {
        return $this->reviewerId;
    }

    public function getSkillId(): int
    {
        return $this->skillId;
    }
}
