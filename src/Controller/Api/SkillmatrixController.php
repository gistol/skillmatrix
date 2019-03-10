<?php declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\RatingDTO;
use App\Repository\Exceptions\InvalidSkillmatrix;
use App\Service\Exceptions\RatingNotAdded;
use App\Service\SkillmatrixService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

class SkillmatrixController extends AbstractFOSRestController
{
    /**
     * @var \App\Service\SkillmatrixService
     */
    private $skillmatrixService;

    public function __construct(SkillmatrixService $skillmatrixService)
    {
        $this->skillmatrixService = $skillmatrixService;
    }

    /**
     * @Rest\Get("/skillmatrix")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getSkillmatrixAction(): View
    {
        try {
            $skillmatrix = $this->skillmatrixService->get();
        } catch (InvalidSkillmatrix $e) {
            $body = ['code' => Response::HTTP_BAD_REQUEST, 'message' => $e->getMessage()];

            return View::create($body, Response::HTTP_BAD_REQUEST);
        }

        return View::create($skillmatrix, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/skillmatrix/rating")
     *
     * @ParamConverter("ratingDTO", class="App\DTO\RatingDTO", converter="fos_rest.request_body")
     *
     * @param \App\DTO\RatingDTO $ratingDTO
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postAddRatingAction(RatingDTO $ratingDTO): View
    {
        try {
            $skillmatrix = $this->skillmatrixService->get();
        } catch (InvalidSkillmatrix $e) {
            $body = ['code' => Response::HTTP_BAD_REQUEST, 'message' => $e->getMessage()];

            return View::create($body, Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->skillmatrixService->addRating($skillmatrix, $ratingDTO);
        } catch (RatingNotAdded $e) {
            $body = ['code' => Response::HTTP_BAD_REQUEST, 'message' => $e->getMessage()];

            return View::create($body, Response::HTTP_BAD_REQUEST);
        }

        return View::create(null, Response::HTTP_CREATED);
    }
}
