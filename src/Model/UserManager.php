<?php


namespace App\Model;


use App\DTO\UserDTO;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\UserRole;
use App\Repository\UserRepository;
use App\Repository\UserRoleRepository;
use App\Repository\RoleRepository;
use App\Util\UserManagementUtility;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;

class UserManager implements UserManagerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RoleRespository
     */
    private $roleRespository;

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
     * @return mixed
     */
    public function createUser(UserDTO $userDTO)
    {
        $permissions=$this->userManagementUtility->getUserPermissions($userDTO->getInitiator());
        foreach ($permissions as $key => $value){
            $initiatorAction=$this->userManagementUtility->generateInitiatorAction($userDTO->getRole(),"ADD");
            if ($initiatorAction==$value->{'name'}){
                $this->saveDetails($userDTO);
            }
        }
        return $this;
    }

    private function saveDetails($userDTO) :void
    {
        $userId=Uuid::uuid1();
        $this->saveUser($userDTO, $userId);
        $this->saveUserRole($userDTO, $userId);
    }

    /**
     * @param $userDTO
     * @param \Ramsey\Uuid\UuidInterface $userId
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function saveUser($userDTO, $userId): void
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
     * @param \Ramsey\Uuid\UuidInterface $userId
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function saveUserRole($userDTO,$userId): void
    {
        //TODO: role Details can be cached to avoid DB calls.
        $role = $this->roleRepository->findByName($userDTO->getRole());
        $userRole = new UserRole();
        $userRole->setCreatedOn(time());
        $userRole->setUpdatedBy($userDTO->getInitiator());
        $userRole->setRoleId($role->getId());
        $userRole->setUserId($userId);
        $this->userRoleRepository->save($userRole);
    }

}