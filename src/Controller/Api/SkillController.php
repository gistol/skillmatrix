<?php declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\SkillDTO;
use App\Repository\Skill\SkillNotDeleted;
use App\Repository\Skill\SkillNotFound;
use App\Repository\Skill\SkillNotSaved;
use App\Service\SkillService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SkillController extends AbstractFOSRestController
{
    /**
     * @var \App\Service\SkillService
     */
    private $skillService;

    public function __construct(SkillService $skillService)
    {
        $this->skillService = $skillService;
    }

    /**
     * @Rest\Post("/skills")
     *
     * @ParamConverter("skillDTO", class="App\DTO\SkillDTO", converter="fos_rest.request_body")
     *
     * @param \App\DTO\SkillDTO $skillDTO
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postAddAction(SkillDTO $skillDTO): View
    {
        try {
            $this->skillService->create($skillDTO);
        } catch (SkillNotSaved $e) {
            return View::create(null, Response::HTTP_BAD_REQUEST);
        }

        return View::create(null, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Get("/skills")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getAllAction(): View
    {
        $skills = $this->skillService->getAll();

        return View::create($skills, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/skills/{skillId}")
     *
     * @param int $skillId
     *
     * @return \FOS\RestBundle\View\View
     */
    public function removeSkillAction(int $skillId): View
    {
        try {
            $this->skillService->delete($skillId);
        } catch (SkillNotDeleted $e) {
            return View::create(null, Response::HTTP_BAD_REQUEST);
        } catch (SkillNotFound $e) {
            return View::create(null, Response::HTTP_NOT_FOUND);
        }

        return View::create(null, Response::HTTP_OK);
    }

    /**
     * @Rest\Patch("/skills")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \FOS\RestBundle\View\View
     */
    public function renameSkillAction(Request $request): View
    {
        try {
            $this->skillService->rename($request->request->getInt('id'), $request->request->get('name'));
        } catch (SkillNotSaved $e) {
            return View::create(null, Response::HTTP_BAD_REQUEST);
        } catch (SkillNotFound $e) {
            return View::create(null, Response::HTTP_NOT_FOUND);
        } catch (\InvalidArgumentException $e) {
            $body = ['code' => Response::HTTP_BAD_REQUEST, 'message' => $e->getMessage()];

            return View::create($body, Response::HTTP_BAD_REQUEST);
        }

        return View::create(null, Response::HTTP_OK);
    }
}
