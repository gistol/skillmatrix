<?php declare(strict_types=1);

namespace App\Repository\Skill;

use App\Entity\Skill;

interface SkillRepository
{
    /**
     * @param int $skillId
     *
     * @return \App\Entity\Skill
     *
     * @throws \App\Repository\Skill\SkillNotFound
     */
    public function getById(int $skillId): Skill;

    /**
     * @return \App\Entity\Skill[]
     */
    public function getAll(): array;

    /**
     * @param \App\Entity\Skill $skill
     *
     * @throws \App\Repository\Skill\SkillNotSaved
     */
    public function store(Skill $skill): void;

    /**
     * @param \App\Entity\Skill $skill
     *
     * @throws \App\Repository\Skill\SkillNotDeleted
     */
    public function delete(Skill $skill): void;
}
