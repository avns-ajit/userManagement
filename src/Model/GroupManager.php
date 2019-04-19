<?php


namespace App\Model;


use App\Constant\UserManagementConstants;
use App\DTO\GroupDTO;
use App\DTO\UserGroupDTO;
use App\DTO\DeleteGroupDTO;
use App\Entity\Group;
use App\Entity\UserGroup;
use App\Exception\UserManagementException;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use App\Repository\UserGroupRespository;
use App\Util\UserManagementUtility;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class GroupManager implements GroupManagerInterface
{

    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserGroupRespository
     */
    private $userGroupRespository;

    /**
     * @var UserManagementUtility
     */
    private $userManagementUtility;


    public function __construct(GroupRepository $groupRepository,UserManagementUtility $userManagementUtility,UserRepository $userRepository,UserGroupRespository $userGroupRespository)
    {
        $this->groupRepository = $groupRepository;
        $this->userManagementUtility = $userManagementUtility;
        $this->userRepository = $userRepository;
        $this->userGroupRespository = $userGroupRespository;
    }


    /**
     * @param GroupDTO $groupDTO
     * @return mixed
     */
    public function createGroup(GroupDTO $groupDTO)
    {
        $initiatorPermissions=$this->userManagementUtility->getUserPermissions($groupDTO->getInitiator());
        foreach ($initiatorPermissions as $key => $value){
            $initiatorAction=$this->userManagementUtility->generateInitiatorAction("GROUP","CREATE");
            if (strcmp($initiatorAction, $value->{'name'})==0){
                $groupId=$this->saveGroup($groupDTO);
                return $groupId;
            }
        }
        throw new UserManagementException(UserManagementConstants::NOT_AUTHORIZED,Response::HTTP_FORBIDDEN);
    }

    public function deleteGroup(DeleteGroupDTO $deleteGroupRequest)
    {
        $initiatorPermissions=$this->userManagementUtility->getUserPermissions($deleteGroupRequest->getInitiator());
        foreach ($initiatorPermissions as $key => $value){
            $initiatorAction=$this->userManagementUtility->generateInitiatorAction("GROUP","DELETE");
            if (strcmp($initiatorAction, $value->{'name'})==0){
                $group= $this->groupRepository->findByGroup($deleteGroupRequest->getGroup());
                $this->userGroupRespository->checkUsersInGroup($deleteGroupRequest->getGroup());
                $this->groupRepository->delete($group);
            }
        }
        throw new UserManagementException(UserManagementConstants::NOT_AUTHORIZED,Response::HTTP_FORBIDDEN);
    }

    private function saveGroup($groupDTO)
    {
        $groupId=Uuid::uuid1();
        $group = new Group();
        $group->setCreatedOn(time());
        $group->setUpdatedBy($groupDTO->getInitiator());
        $group->setGroupId($groupId);
        $group->setName($groupDTO->getName());
        $this->groupRepository->save($group);
        return $groupId;
    }

    /**
     * @param UserGroupDTO $userGroupRequest
     * @return $this
     */
    public function addToGroup(UserGroupDTO $userGroupDTO)
    {
        $this->groupRepository->findByGroup($userGroupDTO->getGroup());
        $this->userGroupRespository->checkIfGroupAssigned($userGroupDTO->getUser(),$userGroupDTO->getGroup());
        $this->saveUserGroup($userGroupDTO);
        return $this;

    }

    public function removeFromGroup(UserGroupDTO $userGroupDTO)
    {
        $this->groupRepository->findByGroup($userGroupDTO->getGroup());
        $userGroup= $this->userGroupRespository->findUserGroup($userGroupDTO->getUser(),$userGroupDTO->getGroup());
        $this->userGroupRespository->delete($userGroup);
        return $this;
    }

    private function saveUserGroup($userGroupDTO): void
    {
        $userGroup = new UserGroup();
        $userGroup->setCreatedOn(time());
        $userGroup->setUpdatedBy($userGroupDTO->getInitiator());
        $userGroup->setGroupId($userGroupDTO->getGroup());
        $userGroup->setUserId($userGroupDTO->getUser());
        $this->userGroupRespository->save($userGroup);
    }


}