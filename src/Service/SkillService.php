<?php declare(strict_types=1);

namespace App\Service;

use App\DTO\SkillDTO;
use App\Entity\Skill;
use App\Repository\Rating\RatingRepository;
use App\Repository\Skill\SkillRepository;

final class SkillService
{
    /**
     * @var \App\Repository\Skill\SkillRepository
     */
    private $skillRepository;

    /**
     * @var \App\Repository\Rating\RatingRepository
     */
    private $ratingRepository;

    public function __construct(SkillRepository $skillRepository, RatingRepository $ratingRepository)
    {
        $this->skillRepository = $skillRepository;
        $this->ratingRepository = $ratingRepository;
    }

    /**
     * @param \App\DTO\SkillDTO $skillDTO
     *
     * @throws \App\Repository\Skill\SkillNotSaved
     */
    public function create(SkillDTO $skillDTO): void
    {
        $skill = new Skill($skillDTO->getName());

        $this->skillRepository->store($skill);
    }

    /**
     * @param int $skillId
     *
     * @return \App\Entity\Skill
     *
     * @throws \App\Repository\Skill\SkillNotFound
     */
    public function getById(int $skillId): Skill
    {
        return $this->skillRepository->getById($skillId);
    }

    /**
     * @return \App\Entity\Skill[]
     */
    public function getAll(): array
    {
        return $this->skillRepository->getAll();
    }

    /**
     * @param int $skillId
     *
     * @throws \App\Repository\Skill\SkillNotDeleted
     * @throws \App\Repository\Skill\SkillNotFound
     */
    public function delete(int $skillId): void
    {
        $skill = $this->skillRepository->getById($skillId);

        // First delete all rating with Skill
        $this->ratingRepository->deleteBySkill($skill);

        // Then delete Skill
        $this->skillRepository->delete($skill);
    }

    /**
     * @param int $skillId
     * @param string $newName
     *
     * @throws \InvalidArgumentException
     * @throws \App\Repository\Skill\SkillNotFound
     * @throws \App\Repository\Skill\SkillNotSaved
     */
    public function rename(int $skillId, string $newName): void
    {
        $skill = $this->skillRepository->getById($skillId);
        $skill->rename($newName);
        $this->skillRepository->store($skill);
    }
}
