<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Reviewer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ReviewerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $reviewer = new Reviewer('Tomáš');
        $manager->persist($reviewer);
        $this->addReference('reviewer-tomas', $reviewer);

        $reviewer = new Reviewer('Andrej');
        $manager->persist($reviewer);
        $this->addReference('reviewer-andrej', $reviewer);

        $manager->flush();
    }
}
