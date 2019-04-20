<?php


namespace App\Model;


use App\DTO\GroupDTO;
use App\DTO\UserGroupDTO;
use App\DTO\DeleteGroupDTO;

interface GroupManagerInterface
{

    /**
     * @param GroupDTO $groupDTO
     * @return mixed
     */
    public function createGroup(GroupDTO $groupDTO);

    /**
     * @param UserGroupDTO $userGroupDTO
     * @return mixed
     */
    public function addToGroup(UserGroupDTO $userGroupDTO);

    /**
     * @param UserGroupDTO $userGroupDTO
     * @return mixed
     */
    public function removeFromGroup(UserGroupDTO $userGroupDTO);

    /**
     * @param DeleteGroupDTO $deleteGroupDTO
     * @return mixed
     */
    public function deleteGroup(DeleteGroupDTO $deleteGroupDTO);

}