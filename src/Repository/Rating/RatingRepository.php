<?php declare(strict_types=1);

namespace App\Repository\Rating;

use App\Entity\Rating;
use App\Entity\Skill;
use Doctrine\Common\Collections\ArrayCollection;

interface RatingRepository
{
    /**
     * @param int $ratingId
     *
     * @return \App\Entity\Rating
     *
     * @throws \App\Repository\Rating\RatingNotFound
     */
    public function getById(int $ratingId): Rating;

    /**
     * @return \App\Entity\Rating[]
     */
    public function getAll(): array;

    /**
     * @param \App\Entity\Rating $rating
     *
     * @throws \App\Repository\Rating\RatingNotSaved
     */
    public function store(Rating $rating): void;

    /**
     * @param \App\Entity\Rating[]|\Doctrine\Common\Collections\ArrayCollection $ratings
     */
    public function updateAll(ArrayCollection $ratings): void;

    /**
     * @param \App\Entity\Skill $skill
     */
    public function deleteBySkill(Skill $skill): void;
}
