<?php


namespace App\Model;


use App\DTO\UserDTO;
use App\DTO\DeleteUserRequest;
use Exception;

interface UserManagerInterface
{

    /**
     * @param UserDTO $userDTO
     * @return mixed
     */
    public function create(UserDTO $userDTO);

    /**
     * @param DeleteUserRequest $deleteUserRequest
     * @return mixed
     */
    public function delete(DeleteUserRequest $deleteUserRequest);

}