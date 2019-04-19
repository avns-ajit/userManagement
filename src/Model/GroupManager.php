<?php


namespace App\Model;


use App\DTO\GroupDTO;
use App\DTO\UserGroupRequest;
use App\Entity\Group;
use App\Entity\UserGroup;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use App\Repository\UserGroupRespository;
use App\Util\UserManagementUtility;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Ramsey\Uuid\Uuid;

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
        $permissions=$this->userManagementUtility->getUserPermissions($groupDTO->getInitiator());
        foreach ($permissions as $key => $value){
            if ("GROUP_CREATE"==$value->{'name'}){
                $this->saveGroup($groupDTO);
            }
        }
        return $this;
    }

    private function saveGroup($groupDTO): void
    {
        $group = new Group();
        $group->setCreatedOn(time());
        $group->setUpdatedBy($groupDTO->getInitiator());
        $group->setGroupId(Uuid::uuid1());
        $group->setName($groupDTO->getName());
        try {
            $this->groupRepository->save($group);
        }  catch (Exception $e) {
            print_r($e);
        }
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