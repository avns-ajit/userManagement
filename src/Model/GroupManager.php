<?php


namespace App\Model;


use App\Constant\UserManagementConstants;
use App\DTO\GroupDTO;
use App\DTO\UserGroupRequest;
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
                $isGroupAssigned= $this->userGroupRespository->isGroupMapped($deleteGroupRequest->getGroup());
                if($isGroupAssigned)
                    return $this;
                $this->groupRepository->delete($deleteGroupRequest->getGroup());
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
     * @param UserGroupRequest $userGroupRequest
     * @return $this
     */
    public function addToGroup(UserGroupRequest $userGroupRequest)
    {
        $isGroupAssigned= $this->userGroupRespository->isGroupAssigned($userGroupRequest->getUser(),$userGroupRequest->getGroup());
        if($isGroupAssigned)
            return $this;
        $this->saveUserGroup($userGroupRequest);
        return $this;

    }

    private function saveUserGroup($userGroupRequest): void
    {
        $userGroup = new UserGroup();
        $userGroup->setCreatedOn(time());
        $userGroup->setUpdatedBy($userGroupRequest->getInitiator());
        $userGroup->setGroupId($userGroupRequest->getGroup());
        $userGroup->setUserId($userGroupRequest->getUser());
        try {
            $this->userGroupRespository->save($userGroup);
        }  catch (Exception $e) {
            print_r($e);
        }
    }

    public function removeFromGroup(UserGroupRequest $userGroupRequest)
    {
        $userGroup= $this->userGroupRespository->findUserGroup($userGroupRequest->getUser(),$userGroupRequest->getGroup());
        if(isset($userGroup))
            $this->userGroupRespository->delete($userGroup);
        return $this;
    }

}