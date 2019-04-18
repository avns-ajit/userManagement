<?php


namespace App\Util;


use App\Repository\RoleRepository;
use App\Repository\UserRepository;

class UserManagementUtility
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RoleRespository
     */
    private $roleRespository;


    public function __construct(UserRepository $userRepository,RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;

    }
    public function getUserPermissions($user){

        $data=$this->userRepository->findByUser($user);
        $array = $data->getRoles()->getValues();
        print_r($array);
        $roleIds = array();
        foreach ($data->getRoles() as $key => $value) {
            $roleId=$value->{'id'};
            $roleIds[] = $roleId;
        }
        $dd=$this->roleRepository->findPermissionsForRoles($roleIds);
        return $dd;

    }

    public function generateInitiatorAction($role,$action)
    {
        $seperator="_";
        return $role.$seperator.$action;
    }
}