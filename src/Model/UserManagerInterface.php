<?php


namespace App\Model;


use App\DTO\UserDTO;
use App\DTO\DeleteUserDTO;

interface UserManagerInterface
{

    /**
     * @param UserDTO $userDTO
     * @return mixed
     */
    public function create(UserDTO $userDTO);

    /**
     * @param DeleteUserDTO $deleteUserDTO
     * @return mixed
     */
    public function delete(DeleteUserDTO $deleteUserDTO);

}