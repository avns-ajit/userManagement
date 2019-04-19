<?php


namespace App\Model;


use App\DTO\GroupDTO;

interface GroupManagerInterface
{

    /**
     * @param GroupDTO $groupDTO
     * @return mixed
     */
    public function createGroup(GroupDTO $groupDTO);
}