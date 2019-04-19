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

    public function addToGroup(UserGroupDTO $userGroupDTO);

    public function removeFromGroup(UserGroupDTO $userGroupDTO);

    public function deleteGroup(DeleteGroupDTO $deleteGroupDTO);

}