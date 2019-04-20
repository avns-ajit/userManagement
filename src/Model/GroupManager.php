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
     * @return mixed|\Ramsey\Uuid\UuidInterface
     */
    public function createGroup(GroupDTO $groupDTO)
    {
        $initiatorPermissions=$this->userManagementUtility->checkPermissions($groupDTO->getInitiator());
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
        $group= $this->groupRepository->checkGroup($deleteGroupRequest->getGroup());
        $initiatorPermissions=$this->userManagementUtility->checkPermissions($deleteGroupRequest->getInitiator());
        foreach ($initiatorPermissions as $key => $value){
            $initiatorAction=$this->userManagementUtility->generateInitiatorAction("GROUP","DELETE");
            if (strcmp($initiatorAction, $value->{'name'})==0){
                $this->userGroupRespository->checkUsersInGroup($deleteGroupRequest->getGroup());
                $this->groupRepository->delete($group);
                return;
            }
        }
        throw new UserManagementException(UserManagementConstants::NOT_AUTHORIZED,Response::HTTP_FORBIDDEN);
    }

    /**
     * @param UserGroupDTO $userGroupDTO
     * @return $this|mixed
     */
    public function addToGroup(UserGroupDTO $userGroupDTO)
    {
        $user= $this->userRepository->checkUser($userGroupDTO->getUser());
        $initiatorPermissions=$this->userManagementUtility->checkPermissions($userGroupDTO->getInitiator());
        foreach ($initiatorPermissions as $key => $value){
            $initiatorAction=$this->userManagementUtility->generateInitiatorAction("GROUP","ADD");
            if (strcmp($initiatorAction, $value->{'name'})==0){
                $this->groupRepository->checkGroup($userGroupDTO->getGroup());
                $this->userGroupRespository->checkIfGroupAssigned($userGroupDTO->getUser(),$userGroupDTO->getGroup());
                $this->saveUserGroup($userGroupDTO);
                return;
            }
        }
        throw new UserManagementException(UserManagementConstants::NOT_AUTHORIZED,Response::HTTP_FORBIDDEN);
    }

    /**
     * @param UserGroupDTO $userGroupDTO
     * @return $this|mixed
     */
    public function removeFromGroup(UserGroupDTO $userGroupDTO)
    {
        $user= $this->userRepository->checkUser($userGroupDTO->getUser());
        $initiatorPermissions=$this->userManagementUtility->checkPermissions($userGroupDTO->getInitiator());
        foreach ($initiatorPermissions as $key => $value){
            $initiatorAction=$this->userManagementUtility->generateInitiatorAction("GROUP","REMOVE");
            if (strcmp($initiatorAction, $value->{'name'})==0){
                $this->groupRepository->checkGroup($userGroupDTO->getGroup());
                $userGroup= $this->userGroupRespository->checkIfGroupHasUser($userGroupDTO->getUser(),$userGroupDTO->getGroup());
                $this->userGroupRespository->delete($userGroup);
                return;
            }
        }
        throw new UserManagementException(UserManagementConstants::NOT_AUTHORIZED,Response::HTTP_FORBIDDEN);
    }

    /**
     * @param $userGroupDTO
     */
    private function saveUserGroup($userGroupDTO): void
    {
        $userGroup = new UserGroup();
        $userGroup->setCreatedOn(time());
        $userGroup->setUpdatedBy($userGroupDTO->getInitiator());
        $userGroup->setGroupId($userGroupDTO->getGroup());
        $userGroup->setUserId($userGroupDTO->getUser());
        $this->userGroupRespository->save($userGroup);
    }

    /**
     * @param $groupDTO
     * @return \Ramsey\Uuid\UuidInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
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


}