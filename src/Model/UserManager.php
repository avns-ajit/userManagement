<?php


namespace App\Model;


use App\DTO\DeleteUserDTO;
use App\DTO\UserDTO;
use App\Entity\User;
use App\Entity\UserRole;
use App\Constant\UserManagementConstants;
use App\Exception\UserManagementException;
use App\Repository\UserRepository;
use App\Repository\UserRoleRepository;
use App\Repository\RoleRepository;
use App\Util\UserManagementUtility;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class UserManager implements UserManagerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * @var UserManagementUtility
     */
    private $userManagementUtility;

    /**
     * @var UserRoleRepository
     */
    private $userRoleRepository;


    public function __construct(UserRepository $userRepository,RoleRepository $roleRepository,UserRoleRepository $userRoleRepository,UserManagementUtility $userManagementUtility)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->userManagementUtility = $userManagementUtility;
        $this->userRoleRepository = $userRoleRepository;

    }

    /**
     * @param UserDTO $userDTO
     * @return mixed|\Ramsey\Uuid\UuidInterface
     * @throws Exception
     */
    public function create(UserDTO $userDTO)
    {
        $initiatorPermissions=$this->userManagementUtility->checkPermissions($userDTO->getInitiator());
        print_r($initiatorPermissions);
        foreach ($initiatorPermissions as $key => $value){
            $initiatorAction=$this->userManagementUtility->generateInitiatorAction($userDTO->getRole(),"CREATE");
            if (strcmp($initiatorAction, $value->{'name'})==0){
                $role = $this->roleRepository->checkRole($userDTO->getRole());
                $userId=$this->saveDetails($userDTO,$role);
                return $userId;
            }
        }
        throw new UserManagementException(UserManagementConstants::NOT_AUTHORIZED,Response::HTTP_FORBIDDEN);
    }

    /**
     * @param DeleteUserDTO $deleteUserDTO
     * @return $this|mixed
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(DeleteUserDTO $deleteUserDTO)
    {
        $user= $this->userRepository->checkUser($deleteUserDTO->getUser());
        $initiatorPermissions=$this->userManagementUtility->checkPermissions($deleteUserDTO->getInitiator());
        foreach ($initiatorPermissions as $key => $value){
            foreach ($user->getRoles() as $role){
                print_r($role);
                $initiatorAction=$this->userManagementUtility->generateInitiatorAction($role->{'name'},"DELETE");
                if (strcmp($initiatorAction, $value->{'name'})==0){
                    $this->userRepository->delete($user);
                    return $this;
                }
            }
        }
        throw new UserManagementException(UserManagementConstants::NOT_AUTHORIZED,Response::HTTP_FORBIDDEN);
    }


    /**
     * @param $userDTO
     * @param $role
     * @return \Ramsey\Uuid\UuidInterface
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function saveDetails($userDTO,$role)
    {
        $userId=Uuid::uuid1();
        $this->saveUser($userDTO, $userId);
        $this->saveUserRole($userDTO, $userId,$role);
        return $userId;
    }

    /**
     * @param $userDTO
     * @param $userId
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function saveUser($userDTO, $userId)
    {
        $user = new User();
        $user->setCreatedOn(time());
        $user->setUpdatedBy($userDTO->getInitiator());
        $user->setUserId($userId);
        $user->setName($userDTO->getName());
        $this->userRepository->save($user);
    }

    /**
     * @param $userDTO
     * @param $userId
     * @param $role
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function saveUserRole($userDTO,$userId,$role)
    {
        $userRole = new UserRole();
        $userRole->setCreatedOn(time());
        $userRole->setUpdatedBy($userDTO->getInitiator());
        $userRole->setRoleId($role->getId());
        $userRole->setUserId($userId);
        $this->userRoleRepository->save($userRole);
    }

}