<?php declare(strict_types=1);

namespace App\Service;

use App\DTO\PersonDTO;
use App\Entity\Person;
use App\Repository\Person\PersonRepository;

final class PersonService
{
    /**
     * @var \App\Repository\Person\PersonRepository
     */
    private $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * @param \App\DTO\PersonDTO $personDTO
     *
     * @throws \App\Repository\Person\PersonNotSaved
     */
    public function create(PersonDTO $personDTO): void
    {
        $person = new Person($personDTO->getName());

        $this->personRepository->store($person);
    }

    /**
     * @param int $personId
     *
     * @return \App\Entity\Person
     *
     * @throws \App\Repository\Person\PersonNotFound
     */
    public function getById(int $personId): Person
    {
        return $this->personRepository->getById($personId);
    }

    /**
     * @return \App\Entity\Person[]
     */
    public function getAll(): array
    {
        return $this->personRepository->getAll();
    }

    /**
     * @param int $personId
     *
     * @throws \App\Repository\Person\PersonNotDeleted
     * @throws \App\Repository\Person\PersonNotFound
     */
    public function delete(int $personId): void
    {
        $person = $this->personRepository->getById($personId);

        $this->personRepository->delete($person);
    }

    /**
     * @param int $personId
     * @param string $newName
     *
     * @throws \InvalidArgumentException
     * @throws \App\Repository\Person\PersonNotFound
     * @throws \App\Repository\Person\PersonNotSaved
     */
    public function rename(int $personId, string $newName): void
    {
        $person = $this->personRepository->getById($personId);
        $person->rename($newName);
        $this->personRepository->store($person);
    }
}
