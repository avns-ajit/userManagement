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

    /**
     * @param $user
     * @return mixed
     */
    public function getUserPermissions($user){

        $userDetails=$this->userRepository->findByUser($user);
        $roleIds = array();
        foreach ($userDetails->getRoles() as $key => $value) {
            $roleId=$value->{'id'};
            $roleIds[] = $roleId;
        }
        $permissions=$this->roleRepository->findPermissionsForRoles($roleIds);
        return $permissions;
    }


    public function generateInitiatorAction($role,$action)
    {
        $seperator="_";
        return $role.$seperator.$action;
    }
}