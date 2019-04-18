<?php


namespace App\Model;


use App\DTO\UserDTO;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\UserRoleRespository;
use Doctrine\Common\Collections\ArrayCollection;
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
        $data=$this->userRepository->findByUser($userDTO->getUser());
        $array = $data->getRoles()->getValues();
        print_r($array) ;
        $roleIds = array();
        foreach ($data->getRoles() as $key => $value) {
            $roleId=$value->{'id'};
            $roleIds[] = $roleId;
        }
        print_r($roleIds) ;
        $user= new User();
        $user->setCreatedOn(time());
        $user->setUpdatedBy("System");
        $user->setUserId(Uuid::uuid1());
        $user->setName($userDTO->getName());
        $this->userRepository->save($user);
        return $this;
    }

}