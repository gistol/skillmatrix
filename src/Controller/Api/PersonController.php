<?php declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\PersonDTO;
use App\Repository\Person\PersonNotDeleted;
use App\Repository\Person\PersonNotFound;
use App\Repository\Person\PersonNotSaved;
use App\Service\PersonService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonController extends AbstractFOSRestController
{
    /**
     * @var \App\Service\PersonService
     */
    private $personService;

    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
    }

    /**
     * @Rest\Post("/persons")
     *
     * @ParamConverter("personDTO", class="App\DTO\PersonDTO", converter="fos_rest.request_body")
     *
     * @param \App\DTO\PersonDTO $personDTO
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postAddAction(PersonDTO $personDTO): View
    {
        try {
            $this->personService->create($personDTO);
        } catch (PersonNotSaved $e) {
            return View::create(null, Response::HTTP_BAD_REQUEST);
        }

        return View::create(null, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Get("/persons")
     *
     * @RestView(serializerGroups={"withoutRatings"})
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getAllAction(): View
    {
        $persons = $this->personService->getAll();

        return View::create($persons, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/persons/{personId}")
     *
     * @param int $personId
     *
     * @return \FOS\RestBundle\View\View
     */
    public function removePersonAction(int $personId): View
    {
        try {
            $this->personService->delete($personId);
        } catch (PersonNotDeleted $e) {
            return View::create(null, Response::HTTP_BAD_REQUEST);
        } catch (PersonNotFound $e) {
            return View::create(null, Response::HTTP_NOT_FOUND);
        }

        return View::create(null, Response::HTTP_OK);
    }

    /**
     * @Rest\Patch("/persons")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \FOS\RestBundle\View\View
     */
    public function renamePersonAction(Request $request): View
    {
        try {
            $this->personService->rename($request->request->getInt('id'), $request->request->get('name'));
        } catch (PersonNotSaved $e) {
            return View::create(null, Response::HTTP_BAD_REQUEST);
        } catch (PersonNotFound $e) {
            return View::create(null, Response::HTTP_NOT_FOUND);
        } catch (\InvalidArgumentException $e) {
            // TODO: add error message to response: {"code": 400, "message": "Message from exception"}
            return View::create(null, Response::HTTP_BAD_REQUEST);
        }

        return View::create(null, Response::HTTP_OK);
    }
}
