<?php


namespace App\Model;


use App\DTO\GroupDTO;
use App\DTO\UserGroupRequest;
use App\DTO\DeleteGroupDTO;

interface GroupManagerInterface
{

    /**
     * @param GroupDTO $groupDTO
     * @return mixed
     */
    public function createGroup(GroupDTO $groupDTO);

    public function addToGroup(UserGroupRequest $userGroupRequest);

    public function removeFromGroup(UserGroupRequest $userGroupRequest);

    public function deleteGroup(DeleteGroupDTO $deleteGroupRequest);

}