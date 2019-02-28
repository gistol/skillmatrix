<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\SkillmatrixService;
use Symfony\Component\HttpFoundation\Response;

class HomepageController
{
    /**
     * @var \App\Service\SkillmatrixService
     */
    private $skillmatrixService;

    public function __construct(SkillmatrixService $skillmatrixService)
    {
        $this->skillmatrixService = $skillmatrixService;
    }

    public function index(): Response
    {
        $skillmatrix = $this->skillmatrixService->get();

        dump(json_encode($skillmatrix));

        dd($skillmatrix);
        return new Response('OK');
    }
}
