<?php declare(strict_types=1);

namespace App\Repository\Person;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method \App\Entity\Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method \App\Entity\Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method \App\Entity\Person[]    findAll()
 * @method \App\Entity\Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Doctrine extends ServiceEntityRepository implements PersonRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function getById(int $personId): Person
    {
        $person = $this->find($personId);

        if ($person === null) {
            $message = sprintf('Person (id=%d) not found.', $personId);

            throw new PersonNotFound($message);
        }

        return $person;
    }

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function store(Person $person): void
    {
        try {
            $this->_em->persist($person);
            $this->_em->flush();
        } catch (\Throwable $e) {
            $message = sprintf('Person (%s) was not be saved.', $person->getName());

            throw new PersonNotSaved($message);
        }
    }

    public function delete(Person $person): void
    {
        try {
            $this->_em->remove($person);
            $this->_em->flush();
        } catch (ORMException $e) {
            $message = sprintf('Person (%s) was not deleted.', $person->getName());

            throw new PersonNotDeleted($message);
        }
    }
}
