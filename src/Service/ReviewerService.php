<?php declare(strict_types=1);

namespace App\Service;

use App\DTO\ReviewerDTO;
use App\Entity\Reviewer;
use App\Repository\Exceptions\UnknownNumberOfRatings;
use App\Repository\Reviewer\ReviewerNotDeleted;
use App\Repository\Reviewer\ReviewerRepository;

final class ReviewerService
{
    /**
     * @var \App\Repository\Reviewer\ReviewerRepository
     */
    private $reviewerRepository;

    public function __construct(ReviewerRepository $reviewerRepository)
    {
        $this->reviewerRepository = $reviewerRepository;
    }

    /**
     * @param \App\DTO\ReviewerDTO $reviewerDTO
     *
     * @throws \App\Repository\Reviewer\ReviewerNotSaved
     */
    public function create(ReviewerDTO $reviewerDTO): void
    {
        $reviewer = new Reviewer($reviewerDTO->getName());

        $this->reviewerRepository->store($reviewer);
    }

    /**
     * @param int $reviewerId
     *
     * @return \App\Entity\Reviewer
     *
     * @throws \App\Repository\Reviewer\ReviewerNotFound
     */
    public function getById(int $reviewerId): Reviewer
    {
        return $this->reviewerRepository->getById($reviewerId);
    }

    /**
     * @return \App\Entity\Reviewer[]
     */
    public function getAll(): array
    {
        return $this->reviewerRepository->getAll();
    }

    /**
     * @param int $reviewerId
     *
     * @throws \App\Repository\Reviewer\ReviewerNotDeleted
     * @throws \App\Repository\Reviewer\ReviewerNotFound
     */
    public function delete(int $reviewerId): void
    {
        $reviewer = $this->reviewerRepository->getById($reviewerId);

        try {
            $ratingsCount = $this->reviewerRepository->getRatingsCount($reviewer);
        } catch (UnknownNumberOfRatings $e) {
            // Throw exception if can not get number of Reviewer's ratings
            throw new ReviewerNotDeleted($e->getMessage());
        }

        // Admin must first delete all Reviewer ratings
        if ($ratingsCount > 0) {
            $message = sprintf('Reviewer (%s) was not deleted because there is %d Ratings added by this Reviewer.', $reviewer->getName(), $ratingsCount);

            throw new ReviewerNotDeleted($message);
        }

        $this->reviewerRepository->delete($reviewer);
    }

    /**
     * @param int $reviewerId
     * @param string $newName
     *
     * @throws \InvalidArgumentException
     * @throws \App\Repository\Reviewer\ReviewerNotSaved
     * @throws \App\Repository\Reviewer\ReviewerNotFound
     */
    public function rename(int $reviewerId, string $newName): void
    {
        $reviewer = $this->reviewerRepository->getById($reviewerId);
        $reviewer->rename($newName);
        $this->reviewerRepository->store($reviewer);
    }
}
