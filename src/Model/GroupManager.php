<?php


namespace App\Model;


use App\DTO\GroupDTO;
use App\Entity\Group;
use App\Repository\GroupRepository;
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
     * @var UserManagementUtility
     */
    private $userManagementUtility;


    public function __construct(GroupRepository $groupRepository,UserManagementUtility $userManagementUtility)
    {
        $this->groupRepository = $groupRepository;
        $this->userManagementUtility = $userManagementUtility;
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
}