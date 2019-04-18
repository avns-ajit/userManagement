<?php


namespace App\Model;


use App\DTO\UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\UserRoleRespository;
use Ramsey\Uuid\Uuid;

class UserManager implements UserManagerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserRoleRespository
     */
    private $userRoleRespository;

    public function __construct(UserRepository $userRepository,UserRoleRespository $userRoleRespository)
    {
        $this->userRepository = $userRepository;
        $this->userRoleRespository = $userRoleRespository;

    }

    /**
     * @param UserDTO $userDTO
     * @return mixed
     */
    public function createUser(UserDTO $userDTO)
    {
        $data=$this->userRoleRespository->findByUser($userDTO->getUser());
        echo $data->getRolePermission()->getPermissionId();
        $user= new User();
        $user->setCreatedOn(time());
        $user->setUpdatedBy("System");
        $user->setUserId(Uuid::uuid1());
        $user->setName($userDTO->getName());
        $this->userRepository->save($user);
        return $this;
    }

}