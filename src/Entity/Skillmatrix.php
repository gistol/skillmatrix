<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Skillmatrix
{
    /**
     * @var \App\Entity\Person[]
     */
    private $persons;

    public function __construct()
    {
        $this->persons = new ArrayCollection();
    }

    public function addPerson(Person $person): void
    {
        $this->persons->add($person);
    }
}
