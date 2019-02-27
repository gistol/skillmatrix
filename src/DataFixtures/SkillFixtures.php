<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SkillFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /*
         * TODO: Add more skills: Design patterns & OOP concepts, Framework knowledge
         */

        $skill = new Skill('Programming Language');
        $manager->persist($skill);

        $skill = new Skill('Database Concepts');
        $manager->persist($skill);

        $skill = new Skill('Debugging & Profiling');
        $manager->persist($skill);

        $skill = new Skill('Client-side Scripting');
        $manager->persist($skill);

        $manager->flush();
    }
}
