<?php


namespace App\Model;


use App\DTO\UserDTO;

interface UserManagerInterface
{

    /**
     * @param UserDTO $userDTO
     * @return UserManagerInterface
     */
    public function createUser(UserDTO $userDTO);
}