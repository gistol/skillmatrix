<?php declare(strict_types=1);

namespace App\Repository\Person;

use App\Entity\Person;

interface PersonRepository
{
    /**
     * @param int $personId
     *
     * @return \App\Entity\Person
     *
     * @throws \App\Repository\Person\PersonNotFound
     */
    public function getById(int $personId): Person;

    /**
     * @return \App\Entity\Person[]
     */
    public function getAll(): array;

    /**
     * @param \App\Entity\Person $person
     *
     * @throws \App\Repository\Person\PersonNotSaved
     */
    public function store(Person $person): void;

    /**
     * @param \App\Entity\Person $person
     *
     * @throws \App\Repository\Person\PersonNotDeleted
     */
    public function delete(Person $person): void;
}
