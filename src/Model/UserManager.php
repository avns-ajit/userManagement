<?php


namespace App\Model;


use App\DTO\UserDTO;

class UserManager implements UserManagerInterface
{

    /**
     * {@inheritdoc}
     *
     */
    public function createUser(UserDTO $userDTO)
    {
        return "ajit";
    }

    public function __toString(){
        return "ajit";
    }

}