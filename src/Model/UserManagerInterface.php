<?php


namespace App\Model;


use App\DTO\UserDTO;

interface UserManagerInterface
{

    /**
     * @param UserDTO $userDTO
     * @return mixed
     */
    public function createUser(UserDTO $userDTO);

}