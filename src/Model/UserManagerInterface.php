<?php


namespace App\Model;


use App\DTO\UserDTO;
use App\DTO\DeleteUserRequest;

interface UserManagerInterface
{

    /**
     * @param UserDTO $userDTO
     * @return mixed
     */
    public function createUser(UserDTO $userDTO);

    /**
     * @param DeleteUserRequest $deleteUserRequest
     * @return mixed
     */
    public function delete(DeleteUserRequest $deleteUserRequest);

}