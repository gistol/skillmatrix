<?php declare(strict_types = 1);

namespace App\DataFixtures;

use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PersonFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $person = new Person('Michal');
        $manager->persist($person);

        $person = new Person('Vlado');
        $manager->persist($person);

        $person = new Person('Tibor');
        $manager->persist($person);

        $manager->flush();
    }
}
