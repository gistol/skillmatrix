<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Skillmatrix implements \JsonSerializable
{
    /**
     * @var \App\Entity\Person[]
     */
    private $persons;

    /**
     * @var \App\Entity\Skill[]
     */
    private $skills;

    public function __construct()
    {
        $this->persons = new ArrayCollection();
        $this->skills = new ArrayCollection();
    }

    public function setPersons(array $persons): void
    {
        /**
         * Nepouzijem jednoduche:
         * $this->persons = $persons;
         * pretoze neviem zarucit ze v poli $persons budu iba objekty typu \App\Entity\Person
         * Funkcia addPerson() ma typehintovany parameter takze vyhodi TypeError pri zlom type parametra
         */
        foreach ($persons as $person) {
            $this->addPerson($person);
        }
    }

    public function getPersons(): Collection
    {
        return $this->persons;
    }

    public function setSkills(array $skills): void
    {
        /**
         * Nepouzijem jednoduche:
         * $this->skills = $skills;
         * pretoze neviem zarucit ze v poli $skills budu iba objekty typu \App\Entity\Skill
         * Funkcia addSkill() ma typehintovany parameter takze vyhodi TypeError pri zlom type parametra
         */
        foreach ($skills as $skill) {
            $this->addSkill($skill);
        }
    }

    public function getSkills(): Collection
    {
        return $this->skills;
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
