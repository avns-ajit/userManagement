<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Model\GroupManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\DTO\GroupDTO;
use App\DTO\UserGroupRequest;
use App\DTO\DeleteGroupRequest;


/**
 *  @Route("/group")
 */
class GroupController extends AbstractController
{

    /**
     * @var GroupManagerInterface
     */
    private $groupManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * GroupController constructor.
     * @param GroupManagerInterface $groupManager
     */
    public function __construct(GroupManagerInterface $groupManager,ValidatorInterface $validator)
    {
        $this->groupManager = $groupManager;
        $this->validator = $validator;
    }

    /**
     * @Route("/create")
     * @ParamConverter("groupDTO", converter="fos_rest.request_body")
     */
    public function create(GroupDTO $groupDTO)
    {
        $errors = $this->validator->validate($groupDTO);
        if (count($errors) > 0) {
            return $this->validationFailedResponse($errors);
        }
        $this->groupManager->createGroup($groupDTO);
        $response = new Response(json_encode($groupDTO->getName()));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/delete")
     * @ParamConverter("deleteGroupRequest", converter="fos_rest.request_body")
     */
    public function delete(DeleteGroupRequest $deleteGroupRequest)
    {
        $errors = $this->validator->validate($deleteGroupRequest);
        if (count($errors) > 0) {
            return $this->validationFailedResponse($errors);
        }
        $this->groupManager->deleteGroup($deleteGroupRequest);
        $response = new Response(json_encode($deleteGroupRequest->getGroup()));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/add")
     * @ParamConverter("userGroupRequest", converter="fos_rest.request_body")
     */
    public function add(UserGroupRequest $userGroupRequest)
    {
        print_r($userGroupRequest);
        $errors = $this->validator->validate($userGroupRequest);
        if (count($errors) > 0) {
            return $this->validationFailedResponse($errors);
        }
        $this->groupManager->addToGroup($userGroupRequest);
        $response = new Response(json_encode($userGroupRequest->getUser()));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/remove")
     * @ParamConverter("userGroupRequest", converter="fos_rest.request_body")
     */
    public function remove(UserGroupRequest $userGroupRequest)
    {
        print_r($userGroupRequest);
        $errors = $this->validator->validate($userGroupRequest);
        if (count($errors) > 0) {
            return $this->validationFailedResponse($errors);
        }
        $this->groupManager->removeFromGroup($userGroupRequest);
        $response = new Response(json_encode($userGroupRequest->getUser()));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    private function validationFailedResponse($errors)
    {
        $errorsString = (string) $errors;
        $response = new Response($errorsString);
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        return $response;
    }

}