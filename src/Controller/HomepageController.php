<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\PersonRepository;
use Symfony\Component\HttpFoundation\Response;

class HomepageController
{
    /**
     * @var \App\Repository\PersonRepository
     */
    private $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function index(): Response
    {
        $persons = $this->personRepository->findAll();

        foreach ($persons as $person) {
            $ratings = $person->getRatings();

            foreach ($ratings as $rating) {
                dump($rating);
            }
        }

        return new Response('OK');
    }
}
