<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Skillmatrix;
use App\Repository\PersonRepository;
use App\Repository\SkillRepository;

final class SkillmatrixService
{
    /**
     * @var \App\Repository\PersonRepository
     */
    private $personRepository;

    /**
     * @var \App\Repository\SkillRepository
     */
    private $skillRepository;

    public function __construct(PersonRepository $personRepository, SkillRepository $skillRepository)
    {
        $this->personRepository = $personRepository;
        $this->skillRepository = $skillRepository;
    }

    public function get(): Skillmatrix
    {
        $skillmatrix = new Skillmatrix();

        // Get all Skills
        $skills = $this->skillRepository->findAll();
        $skillmatrix->setSkills($skills);

        // Get all Persons
        $persons = $this->personRepository->findAll();
        $skillmatrix->setPersons($persons);

        return $skillmatrix;
    }
}
