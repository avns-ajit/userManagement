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
        $groupResponse = $this->deleteGroupResponse($deleteGroupDTO);
        return $this->userManagementUtility->generateJsonResponse($groupResponse,Response::HTTP_OK);
    }

    /**
     * @Route("/add")
     * @ParamConverter("userGroupDTO", converter="fos_rest.request_body")
     */
    public function add(UserGroupDTO $userGroupDTO)
    {
        $errors = $this->validator->validate($userGroupDTO);
        if (count($errors) > 0) {
            $baseResponse=$this->userManagementUtility->createBaseResponse($errors);
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
        $errors = $this->validator->validate($userGroupDTO);
        if (count($errors) > 0) {
            $baseResponse=$this->userManagementUtility->createBaseResponse($errors);
            return $this->userManagementUtility->generateJsonResponse($baseResponse,Response::HTTP_BAD_REQUEST);
        }
        $this->groupManager->removeFromGroup($userGroupDTO);
        $userGroupResponse = $this->createUserGroupResponse($userGroupDTO);
        return $this->userManagementUtility->generateJsonResponse($userGroupResponse,Response::HTTP_OK);
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

    private function createUserGroupResponse(UserGroupDTO $userGroupRequest): UserGroupResponse
    {
        $userGroupResponse = new UserGroupResponse();
        $userGroupResponse->setGroup($userGroupRequest->setGroup());
        $userGroupResponse->setUser($userGroupRequest->setUser());
        $userGroupResponse->setMessage("Group Successfully Created");
        return $userGroupResponse;
    }


}