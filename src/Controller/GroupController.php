<?php


namespace App\Controller;


use App\Entity\Group;
use App\Response\GroupResponse;
use App\Util\UserManagementUtility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Model\GroupManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\DTO\GroupDTO;
use App\DTO\UserGroupRequest;
use App\DTO\DeleteGroupDTO;


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
     * @var UserManagementUtility
     */
    private $userManagementUtility;

    /**
     * GroupController constructor.
     * @param GroupManagerInterface $groupManager
     */
    public function __construct(GroupManagerInterface $groupManager,ValidatorInterface $validator,UserManagementUtility $userManagementUtility)
    {
        $this->groupManager = $groupManager;
        $this->validator = $validator;
        $this->userManagementUtility = $userManagementUtility;
    }

    /**
     * @Route("/create")
     * @ParamConverter("groupDTO", converter="fos_rest.request_body")
     */
    public function create(GroupDTO $groupDTO)
    {
        $errors = $this->validator->validate($groupDTO);
        if (count($errors) > 0) {
            $baseResponse=$this->userManagementUtility->createBaseResponse($errors);
            return $this->userManagementUtility->generateJsonResponse($baseResponse,Response::HTTP_BAD_REQUEST);
        }
        $groupId=$this->groupManager->createGroup($groupDTO);
        $groupResponse = $this->createGroupResponse($groupDTO, $groupId);
        return $this->userManagementUtility->generateJsonResponse($groupResponse,Response::HTTP_OK);
    }

    /**
     * @Route("/delete")
     * @ParamConverter("deleteGroupDTO", converter="fos_rest.request_body")
     */
    public function delete(DeleteGroupDTO $deleteGroupDTO)
    {
        $errors = $this->validator->validate($deleteGroupDTO);
        if (count($errors) > 0) {
            $baseResponse=$this->userManagementUtility->createBaseResponse($errors);
            return $this->userManagementUtility->generateJsonResponse($baseResponse,Response::HTTP_BAD_REQUEST);
        }
        $this->groupManager->deleteGroup($deleteGroupDTO);
        $userResponse = $this->deleteGroupResponse($deleteGroupDTO);
        return $this->userManagementUtility->generateJsonResponse($userResponse,Response::HTTP_OK);
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

    private function createGroupResponse(GroupDTO $groupDTO, $groupId): GroupResponse
    {
        $groupResponse = new GroupResponse();
        $groupResponse->setGroup($groupId);
        $groupResponse->setName($groupDTO->getName());
        $groupResponse->setMessage("Group Successfully Created");
        return $groupResponse;
    }

    private function deleteGroupResponse(DeleteGroupDTO $deleteGroupDTO): GroupResponse
    {
        $groupResponse = new GroupResponse();
        $groupResponse->setGroup($deleteGroupDTO->getGroup());
        $groupResponse->setMessage("Group Successfully Deleted");
        return $groupResponse;
    }


}