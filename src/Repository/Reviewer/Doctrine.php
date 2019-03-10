<?php declare(strict_types=1);

namespace App\Repository\Reviewer;

use App\Entity\Rating;
use App\Entity\Reviewer;
use App\Repository\Exceptions\UnknownNumberOfRatings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method \App\Entity\Reviewer|null find($id, $lockMode = null, $lockVersion = null)
 * @method \App\Entity\Reviewer|null findOneBy(array $criteria, array $orderBy = null)
 * @method \App\Entity\Reviewer[]    findAll()
 * @method \App\Entity\Reviewer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Doctrine extends ServiceEntityRepository implements ReviewerRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reviewer::class);
    }

    public function getById(int $reviewerId): Reviewer
    {
        $reviewer = $this->find($reviewerId);

        if ($reviewer === null) {
            $message = sprintf('Reviewer (id=%d) not found.', $reviewerId);

            throw new ReviewerNotFound($message);
        }

        return $reviewer;
    }

    public function store(Reviewer $reviewer): void
    {
        try {
            $this->_em->persist($reviewer);
            $this->_em->flush();
        } catch (\Throwable $e) {
            $message = sprintf('Reviewer (%s) was not be saved.', $reviewer->getName());

            throw new ReviewerNotSaved($message);
        }
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function delete(Reviewer $reviewer): void
    {
        try {
            $this->_em->remove($reviewer);
            $this->_em->flush();
        } catch (ORMException $e) {
            $message = sprintf('Reviewer (%s) was not deleted.', $reviewer->getName());

            throw new ReviewerNotDeleted($message);
        } catch (ForeignKeyConstraintViolationException $e) {
            // In the DB are Ratings added by this Reviewer
            $message = sprintf('Reviewer (%s) was not deleted because there are Ratings added by this Reviewer.', $reviewer->getName());

            throw new ReviewerNotDeleted($message);
        }
    }

    public function getRatingsCount(Reviewer $reviewer): int
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('COUNT(ratings.id)')
            ->from(Rating::class, 'ratings')
            ->where('ratings.reviewer = :reviewer_id');
        $qb->setParameter('reviewer_id', $reviewer->getId());

        try {
            $count = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            throw new UnknownNumberOfRatings();
        }

        return (int) $count;
    }
}
