<?php declare(strict_types=1);

namespace App\Service;

use App\DTO\RatingDTO;
use App\Entity\Rating;

class RatingService
{
    /**
     * @var \App\Service\PersonService
     */
    private $personService;

    /**
     * @var \App\Service\ReviewerService
     */
    private $reviewerService;

    /**
     * @var \App\Service\SkillService
     */
    private $skillService;

    public function __construct(PersonService $personService, ReviewerService $reviewerService, SkillService $skillService)
    {
        $this->personService = $personService;
        $this->reviewerService = $reviewerService;
        $this->skillService = $skillService;
    }

    /**
     * @param \App\DTO\RatingDTO $ratingDTO
     *
     * @return \App\Entity\Rating
     *
     * @throws \App\Repository\Person\PersonNotFound
     * @throws \App\Repository\Reviewer\ReviewerNotFound
     * @throws \App\Repository\Skill\SkillNotFound
     */
    public function create(RatingDTO $ratingDTO): Rating
    {
        $person = $this->personService->getById($ratingDTO->getPersonId());
        $reviewer = $this->reviewerService->getById($ratingDTO->getReviewerId());
        $skill = $this->skillService->getById($ratingDTO->getSkillId());

        $rating = new Rating($person, $reviewer, $skill, $ratingDTO->getScore());
        $rating->setNote($ratingDTO->getNote());

        return $rating;
    }
}
