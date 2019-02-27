<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Skillmatrix
{
    /** @var \App\Entity\Person[] */
    private $persons;

    public function __construct()
    {
        $this->persons = new ArrayCollection();
    }

    public function addPerson(Person $person)
    {
        $this->persons->add($person);
    }
}
