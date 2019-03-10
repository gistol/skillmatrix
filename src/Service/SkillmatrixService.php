<?php declare(strict_types=1);

namespace App\Service;

use App\DTO\RatingDTO;
use App\Entity\Exceptions\RatingAlreadyExists;
use App\Entity\Skillmatrix;
use App\Repository\Person\PersonNotFound;
use App\Repository\Reviewer\ReviewerNotFound;
use App\Repository\Skill\SkillNotFound;
use App\Repository\SkillmatrixRepository;
use App\Service\Exceptions\RatingNotAdded;

final class SkillmatrixService
{
    /**
     * @var \App\Repository\SkillmatrixRepository
     */
    private $skillmatrixRepository;

    /**
     * @var \App\Service\RatingService
     */
    private $ratingService;

    public function __construct(SkillmatrixRepository $skillmatrixRepository, RatingService $ratingService)
    {
        $this->skillmatrixRepository = $skillmatrixRepository;
        $this->ratingService = $ratingService;
    }

    /**
     * @return \App\Entity\Skillmatrix
     *
     * @throws \App\Repository\Exceptions\InvalidSkillmatrix
     */
    public function get(): Skillmatrix
    {
        return $this->skillmatrixRepository->get();
    }

    /**
     * @param \App\Entity\Skillmatrix $skillmatrix
     * @param \App\DTO\RatingDTO $ratingDTO
     *
     * @throws \App\Service\Exceptions\RatingNotAdded
     */
    public function addRating(Skillmatrix $skillmatrix, RatingDTO $ratingDTO): void
    {
        try {
            $rating = $this->ratingService->create($ratingDTO);
        } catch (PersonNotFound | ReviewerNotFound | SkillNotFound $e) {
            throw new RatingNotAdded($e->getMessage());
        }

        try {
            $skillmatrix->addRating($rating);
        } catch (RatingAlreadyExists $e) {
            throw new RatingNotAdded($e->getMessage());
        }

        $this->save($skillmatrix);
    }

    /**
     * @param \App\Entity\Skillmatrix $skillmatrix
     */
    public function save(Skillmatrix $skillmatrix): void
    {
        $this->skillmatrixRepository->save($skillmatrix);
    }
}
