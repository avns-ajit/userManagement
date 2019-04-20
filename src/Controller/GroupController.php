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
use App\DTO\UserGroupDTO;
use App\DTO\DeleteGroupDTO;
use App\Response\UserGroupResponse;


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
     * @param ValidatorInterface $validator
     * @param UserManagementUtility $userManagementUtility
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
     * @param GroupDTO $groupDTO
     * @return Response
     */
    public function create(GroupDTO $groupDTO)
    {
        $validationFailures = $this->validator->validate($groupDTO);
        if (count($validationFailures) > 0) {
            $baseResponse=$this->userManagementUtility->createBaseResponse($validationFailures);
            return $this->userManagementUtility->generateJsonResponse($baseResponse,Response::HTTP_BAD_REQUEST);
        }
        $groupId=$this->groupManager->createGroup($groupDTO);
        $groupResponse = $this->createGroupResponse($groupDTO, $groupId);
        return $this->userManagementUtility->generateJsonResponse($groupResponse,Response::HTTP_OK);
    }

    /**
     * @Route("/delete")
     * @ParamConverter("deleteGroupDTO", converter="fos_rest.request_body")
     * @param DeleteGroupDTO $deleteGroupDTO
     * @return Response
     */
    public function delete(DeleteGroupDTO $deleteGroupDTO)
    {
        $validationFailures = $this->validator->validate($deleteGroupDTO);
        if (count($validationFailures) > 0) {
            $baseResponse=$this->userManagementUtility->createBaseResponse($validationFailures);
            return $this->userManagementUtility->generateJsonResponse($baseResponse,Response::HTTP_BAD_REQUEST);
        }
        $this->groupManager->deleteGroup($deleteGroupDTO);
        $groupResponse = $this->deleteGroupResponse($deleteGroupDTO);
        return $this->userManagementUtility->generateJsonResponse($groupResponse,Response::HTTP_OK);
    }

    /**
     * @Route("/add")
     * @ParamConverter("userGroupDTO", converter="fos_rest.request_body")
     * @param UserGroupDTO $userGroupDTO
     * @return Response
     */
    public function add(UserGroupDTO $userGroupDTO)
    {
        $validationFailures = $this->validator->validate($userGroupDTO);
        if (count($validationFailures) > 0) {
            $baseResponse=$this->userManagementUtility->createBaseResponse($validationFailures);
            return $this->userManagementUtility->generateJsonResponse($baseResponse,Response::HTTP_BAD_REQUEST);
        }
        $this->groupManager->addToGroup($userGroupDTO);
        $userGroupResponse = $this->createUserGroupResponse($userGroupDTO);
        return $this->userManagementUtility->generateJsonResponse($userGroupResponse,Response::HTTP_OK);
    }

    /**
     * @Route("/remove")
     * @ParamConverter("userGroupDTO", converter="fos_rest.request_body")
     */
    public function remove(UserGroupDTO $userGroupDTO)
    {
        $validationFailures = $this->validator->validate($userGroupDTO);
        if (count($validationFailures) > 0) {
            $baseResponse=$this->userManagementUtility->createBaseResponse($validationFailures);
            return $this->userManagementUtility->generateJsonResponse($baseResponse,Response::HTTP_BAD_REQUEST);
        }
        $this->groupManager->removeFromGroup($userGroupDTO);
        $userGroupResponse = $this->createUserGroupResponse($userGroupDTO);
        return $this->userManagementUtility->generateJsonResponse($userGroupResponse,Response::HTTP_OK);
    }

    /**
     * @param GroupDTO $groupDTO
     * @param $groupId
     * @return GroupResponse
     */
    private function createGroupResponse(GroupDTO $groupDTO, $groupId): GroupResponse
    {
        $groupResponse = new GroupResponse();
        $groupResponse->setGroup($groupId);
        $groupResponse->setName($groupDTO->getName());
        $groupResponse->setMessage("Group Successfully Created");
        return $groupResponse;
    }

    /**
     * @param DeleteGroupDTO $deleteGroupDTO
     * @return GroupResponse
     */
    private function deleteGroupResponse(DeleteGroupDTO $deleteGroupDTO): GroupResponse
    {
        $groupResponse = new GroupResponse();
        $groupResponse->setGroup($deleteGroupDTO->getGroup());
        $groupResponse->setMessage("Group Successfully Deleted");
        return $groupResponse;
    }

    /**
     * @param UserGroupDTO $userGroupRequest
     * @return UserGroupResponse
     */
    private function createUserGroupResponse(UserGroupDTO $userGroupRequest): UserGroupResponse
    {
        $userGroupResponse = new UserGroupResponse();
        $userGroupResponse->setGroup($userGroupRequest->getGroup());
        $userGroupResponse->setUser($userGroupRequest->getUser());
        $userGroupResponse->setMessage("Group Action Successfully");
        return $userGroupResponse;
    }


}