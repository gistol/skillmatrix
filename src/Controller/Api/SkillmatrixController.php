<?php declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\SkillmatrixService;
use Symfony\Component\HttpFoundation\JsonResponse;

class SkillmatrixController
{
    /**
     * @var \App\Service\SkillmatrixService
     */
    private $skillmatrixService;

    public function __construct(SkillmatrixService $skillmatrixService)
    {
        $this->skillmatrixService = $skillmatrixService;
    }

    public function get(): JsonResponse
    {
        $skillmatrix = $this->skillmatrixService->get();

        return new JsonResponse($skillmatrix);
    }
}
