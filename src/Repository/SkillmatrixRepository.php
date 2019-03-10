<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Exceptions\RatingAlreadyExists;
use App\Entity\Skillmatrix;
use App\Repository\Exceptions\InvalidSkillmatrix;
use App\Repository\Person\PersonRepository;
use App\Repository\Rating\RatingRepository;
use App\Repository\Skill\SkillRepository;

class SkillmatrixRepository
{
    /**
     * @var \App\Repository\Person\PersonRepository
     */
    private $personRepository;

    /**
     * @var \App\Repository\Skill\SkillRepository
     */
    private $skillRepository;

    /**
     * @var \App\Repository\Rating\RatingRepository
     */
    private $ratingRepository;

    public function __construct(PersonRepository $personRepository, SkillRepository $skillRepository, RatingRepository $ratingRepository)
    {
        $this->personRepository = $personRepository;
        $this->skillRepository = $skillRepository;
        $this->ratingRepository = $ratingRepository;
    }

    /**
     * @return \App\Entity\Skillmatrix
     *
     * @throws \App\Repository\Exceptions\InvalidSkillmatrix
     */
    public function get(): Skillmatrix
    {
        $skillmatrix = new Skillmatrix();

        // Get all Ratings
        $ratings = $this->ratingRepository->getAll();

        try {
            $skillmatrix->setRatings($ratings);
        } catch (RatingAlreadyExists $e) {
            // Ze budem mat v repository ulozene 2 rovnake Ratingy by nikdy nemalo nastat pretoze pred ukladanim do repozitara to kontrolujem.
            // Vynimku ale zachytavam a povazujem to dalej za logicku chybu, ktora mi nedovoli pokracovat dalej.
            throw new InvalidSkillmatrix($e->getMessage());
        } catch (\InvalidArgumentException $e) {
            // V poli $ratings je nejaky prvok iny ako typu Rating. Takuto maticu neviem zostavit.
            throw new InvalidSkillmatrix($e->getMessage());
        }

        // Get all Skills
        $skills = $this->skillRepository->getAll();

        try {
            $skillmatrix->setSkills($skills);
        } catch (\InvalidArgumentException $e) {
            // V poli $skills je nejaky prvok iny ako typu Skill. Takuto maticu neviem zostavit.
            throw new InvalidSkillmatrix($e->getMessage());
        }

        // Get all Persons
        $persons = $this->personRepository->getAll();

        try {
            $skillmatrix->setPersons($persons);
        } catch (\InvalidArgumentException $e) {
            // V poli $persons je nejaky prvok iny ako typu Person. Takuto maticu neviem zostavit.
            throw new InvalidSkillmatrix($e->getMessage());
        }

        return $skillmatrix;
    }

    public function save(Skillmatrix $skillmatrix): void
    {
        $ratings = $skillmatrix->getRatings();

        $this->ratingRepository->updateAll($ratings);
    }
}
