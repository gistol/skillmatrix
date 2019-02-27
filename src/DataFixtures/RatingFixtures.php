<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Person;
use App\Entity\Rating;
use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class RatingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        /** @var \App\Entity\Reviewer $reviewerTomas */
        $reviewerTomas = $this->getReference('reviewer-tomas');
        /** @var \App\Entity\Reviewer $reviewerAndrej */
        $reviewerAndrej = $this->getReference('reviewer-andrej');

        /**
         * Get all persons and skills and create new Rating for each combination of person & skill
         */
        $persons = $manager->getRepository(Person::class)->findAll();
        $skills = $manager->getRepository(Skill::class)->findAll();

        /**
         * Get reviewers from ReviewerFixtures
         */
        $reviewers = [$reviewerTomas, $reviewerAndrej];

        foreach ($persons as $person) {
            foreach ($skills as $skill) {
                // Create new Rating with random Reviewer and random score
                $rating = new Rating($person, $reviewers[rand(0, 1)], $skill, rand(0, 3)); // TODO: use Score object

                // Randomly add Note
                if (rand(0, 1) === 1) {
                    $rating->setNote($faker->text);
                }

                $manager->persist($rating);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ReviewerFixtures::class,
            PersonFixtures::class,
            SkillFixtures::class,
        ];
    }
}
