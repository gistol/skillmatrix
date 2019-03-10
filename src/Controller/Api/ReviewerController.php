<?php declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\ReviewerDTO;
use App\Repository\Reviewer\ReviewerNotDeleted;
use App\Repository\Reviewer\ReviewerNotFound;
use App\Repository\Reviewer\ReviewerNotSaved;
use App\Service\ReviewerService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewerController extends AbstractFOSRestController
{
    /**
     * @var \App\Service\ReviewerService
     */
    private $reviewerService;

    public function __construct(ReviewerService $reviewerService)
    {
        $this->reviewerService = $reviewerService;
    }

    /**
     * @Rest\Post("/reviewers")
     *
     * @ParamConverter("reviewerDTO", class="App\DTO\ReviewerDTO", converter="fos_rest.request_body")
     *
     * @param \App\DTO\ReviewerDTO $reviewerDTO
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postAddAction(ReviewerDTO $reviewerDTO): View
    {
        try {
            $this->reviewerService->create($reviewerDTO);
        } catch (ReviewerNotSaved $e) {
            return View::create(null, Response::HTTP_BAD_REQUEST);
        }

        return View::create(null, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Get("/reviewers")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getAllAction(): View
    {
        $reviewers = $this->reviewerService->getAll();

        return View::create($reviewers, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/reviewers/{reviewerId}")
     *
     * @param int $reviewerId
     *
     * @return \FOS\RestBundle\View\View
     */
    public function removeReviewerAction(int $reviewerId): View
    {
        try {
            $this->reviewerService->delete($reviewerId);
        } catch (ReviewerNotDeleted $e) {
            $body = ['code' => Response::HTTP_BAD_REQUEST, 'message' => $e->getMessage()];

            return View::create($body, Response::HTTP_BAD_REQUEST);
        } catch (ReviewerNotFound $e) {
            return View::create(null, Response::HTTP_NOT_FOUND);
        }

        return View::create(null, Response::HTTP_OK);
    }

    /**
     * @Rest\Patch("/reviewers")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \FOS\RestBundle\View\View
     */
    public function renameReviewerAction(Request $request): View
    {
        try {
            $this->reviewerService->rename($request->request->getInt('id'), $request->request->get('name'));
        } catch (ReviewerNotSaved $e) {
            return View::create(null, Response::HTTP_BAD_REQUEST);
        } catch (ReviewerNotFound $e) {
            return View::create(null, Response::HTTP_NOT_FOUND);
        } catch (\InvalidArgumentException $e) {
            $body = ['code' => Response::HTTP_BAD_REQUEST, 'message' => $e->getMessage()];

            return View::create($body, Response::HTTP_BAD_REQUEST);
        }

        return View::create(null, Response::HTTP_OK);
    }
}
