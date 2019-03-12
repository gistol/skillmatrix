<?php declare(strict_types=1);

namespace App\Entity;

use App\Entity\Exceptions\RatingAlreadyExists;
use Doctrine\Common\Collections\ArrayCollection;

class Skillmatrix implements \JsonSerializable
{
    /**
     * @var \App\Entity\Person[]|\Doctrine\Common\Collections\ArrayCollection
     */
    private $persons;

    /**
     * @var \App\Entity\Skill[]|\Doctrine\Common\Collections\ArrayCollection
     */
    private $skills;

    /**
     * @var \App\Entity\Rating[]|\Doctrine\Common\Collections\ArrayCollection
     */
    private $ratings;

    public function __construct()
    {
        $this->persons = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    /**
     * @param array $ratings
     *
     * @throws \App\Entity\Exceptions\RatingAlreadyExists
     */
    public function setRatings(array $ratings): void
    {
        /**
         * Nepouzijem jednoduche priradenie:
         * $this->ratings = $ratings;
         * pretoze neviem zarucit, ze v poli $ratings budu iba objekty typu \App\Entity\Rating
         */
        foreach ($ratings as $rating) {
            if (!$rating instanceof Rating) {
                throw new \InvalidArgumentException(sprintf('Expected instance of %s, got %s', Rating::class, $rating));
            }

            $this->addRating($rating);
        }
    }

    public function getRatings(): ArrayCollection
    {
        return $this->ratings;
    }

    public function setPersons(array $persons): void
    {
        /**
         * Nepouzijem jednoduche:
         * $this->persons = $persons;
         * pretoze neviem zarucit, ze v poli $persons budu iba objekty typu \App\Entity\Person
         */
        foreach ($persons as $person) {
            if (!$person instanceof Person) {
                throw new \InvalidArgumentException(sprintf('Expected instance of %s, got %s', Person::class, $person));
            }

            $this->addPerson($person);
        }
    }

    public function getPersons(): ArrayCollection
    {
        return $this->persons;
    }

    public function setSkills(array $skills): void
    {
        /**
         * Nepouzijem jednoduche:
         * $this->skills = $skills;
         * pretoze neviem zarucit, ze v poli $skills budu iba objekty typu \App\Entity\Skill
         */
        foreach ($skills as $skill) {
            if (!$skill instanceof Skill) {
                throw new \InvalidArgumentException(sprintf('Expected instance of %s, got %s', Skill::class, $skill));
            }

            $this->addSkill($skill);
        }
    }

    public function getSkills(): ArrayCollection
    {
        return $this->skills;
    }

    /**
     * @param \App\Entity\Rating $rating
     *
     * @throws \App\Entity\Exceptions\RatingAlreadyExists
     */
    public function addRating(Rating $rating): void
    {
        if ($this->ratingExists($rating)) {
            throw new RatingAlreadyExists('Rating already exists.');
        }

        $this->ratings->add($rating);
    }

    private function ratingExists(Rating $rating): bool
    {
        /** @var \App\Entity\Rating $existingRating */
        foreach ($this->getRatings() as $existingRating) {
            if ($rating->isEqual($existingRating)) {
                return true;
            }
        }

        return false;
    }

    public function jsonSerialize(): array
    {
        /**
         * Manualne serializujem 'persons' pretoze objekty obsahuju cyklicke vazby
         *
         * TODO: preskumat Symfony komponentu Serializer
         */
        $personsArray = [];

        /** @var \App\Entity\Person $person */
        foreach ($this->getPersons() as $person) {
            $ratingRow = [];

            foreach ($person->getRatings() as $rating) {
                $ratingRow[] = [
                    'id' => $rating->getId(),
                    'skill' => [
                        // Manualne serializujem pretoze vynechavam Skill name, staci mi ID
                        'id' => $rating->getSkill()->getId(),
                    ],
                    'score' => $rating->getScore(),
                    'note' => $rating->getNote(),
                    'reviewer' => $rating->getReviewer(),
                ];
            }

            $personsArray[] = [
                'id' => $person->getId(),
                'name' => $person->getName(),
                'rating' => $ratingRow,
            ];
        }

        return [
            'skills' => $this->getSkills()->getValues(),
            'persons' => $personsArray,
        ];
    }

    private function addSkill(Skill $skill): void
    {
        $this->skills->add($skill);
    }

    private function addPerson(Person $person): void
    {
        $this->persons->add($person);
    }
}
