<?php declare(strict_types=1);

namespace App\Repository\Reviewer;

use App\Entity\Reviewer;

interface ReviewerRepository
{
    /**
     * @param int $reviewerId
     *
     * @return \App\Entity\Reviewer
     *
     * @throws \App\Repository\Reviewer\ReviewerNotFound
     */
    public function getById(int $reviewerId): Reviewer;

    /**
     * @param \App\Entity\Reviewer $reviewer
     *
     * @throws \App\Repository\Reviewer\ReviewerNotSaved
     */
    public function store(Reviewer $reviewer): void;

    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @param \App\Entity\Reviewer $reviewer
     *
     * @throws \App\Repository\Reviewer\ReviewerNotDeleted
     */
    public function delete(Reviewer $reviewer): void;

    /**
     * @param \App\Entity\Reviewer $reviewer
     *
     * @return int
     *
     * @throws \App\Repository\Exceptions\UnknownNumberOfRatings
     */
    public function getRatingsCount(Reviewer $reviewer): int;
}
