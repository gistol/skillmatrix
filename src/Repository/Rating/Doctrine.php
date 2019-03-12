<?php declare(strict_types=1);

namespace App\Repository\Rating;

use App\Entity\Rating;
use App\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method \App\Entity\Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method \App\Entity\Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method \App\Entity\Rating[]    findAll()
 * @method \App\Entity\Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Doctrine extends ServiceEntityRepository implements RatingRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    public function getById(int $ratingId): Rating
    {
        $rating = $this->find($ratingId);

        if ($rating === null) {
            $message = sprintf('Rating (id=%d) not found.', $ratingId);

            throw new RatingNotFound($message);
        }

        return $rating;
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function store(Rating $rating): void
    {
        try {
            $this->_em->persist($rating);
            $this->_em->flush();
        } catch (\Throwable $e) {
            $message = sprintf('Rating was not be saved.');

            throw new RatingNotSaved($message);
        }
    }

    public function updateAll(ArrayCollection $ratings): void
    {
        /**
         * Update bezi v dvoch krokoch v transakcii:
         * 1. zmazem vsetky ratingy (nechce sa mi skumat po jednom ktory sa zmenil a ktory nie)
         * 2. ulozim nove
         * Ak sa nepodari nejaky ulozit, revertne spat vsetky zmazane
         */
        $this->_em->beginTransaction();
        $this->deleteAll();

        foreach ($ratings as $rating) {
            try {
                // TODO: Optimalizacia: ukladat bez flush() pocas cyklu a flush() az na konci
                $this->store($rating);
            } catch (RatingNotSaved $e) {
                $this->_em->rollBack();
            }
        }

        $this->_em->commit();
    }

    public function deleteBySkill(Skill $skill): void
    {
        $qb = $this->_em->createQueryBuilder()
            ->delete(Rating::class, 'rating')
            ->where('rating.skill = :skill_id');
        $qb->setParameter('skill_id', $skill->getId());

        $qb->getQuery()->execute();
    }

    private function deleteAll(): void
    {
        $this->_em->createQueryBuilder()
            ->delete(Rating::class);
    }
}
