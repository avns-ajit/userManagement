<?php


namespace App\Model;


use App\DTO\DeleteUserRequest;
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
     * @return mixed|\Ramsey\Uuid\UuidInterface
     * @throws Exception
     */
    public function create(UserDTO $userDTO)
    {
        $initiatorPermissions=$this->userManagementUtility->getUserPermissions($userDTO->getInitiator());
        foreach ($initiatorPermissions as $key => $value){
            $initiatorAction=$this->userManagementUtility->generateInitiatorAction($userDTO->getRole(),"CREATE");
            if (strcmp($initiatorAction, $value->{'name'})==0){
                //TODO: role Details can be cached to avoid DB calls.
                $role = $this->roleRepository->findByName($userDTO->getRole());
                if(!isset($role))
                    throw new UserManagementException(UserManagementConstants::ROLE_NOT_AVAILABLE,Response::HTTP_BAD_REQUEST);
                $userId=$this->saveDetails($userDTO,$role);
                return $userId;

            }
        }
        //TODO : move to error codes and messages.
        throw new UserManagementException(UserManagementConstants::NOT_AUTHORIZED,Response::HTTP_FORBIDDEN);
    }


    private function saveDetails($userDTO,$role)
    {
        $userId=Uuid::uuid1();
        $this->saveUser($userDTO, $userId);
        $this->saveUserRole($userDTO, $userId,$role);
        return $userId;
    }

    /**
     * @param $userDTO
     * @param \Ramsey\Uuid\UuidInterface $userId
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
     * @param \Ramsey\Uuid\UuidInterface $userId
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

    public function delete(DeleteUserRequest $deleteUserRequest)
    {
        $permissions=$this->userManagementUtility->getUserPermissions($deleteUserRequest->getInitiator());
        foreach ($permissions as $key => $value){
            $initiatorAction=$this->userManagementUtility->generateInitiatorAction("USER","DELETE");
            if ($initiatorAction==$value->{'name'}){
                $user= $this->userRepository->findByUser($deleteUserRequest->getUser());
                if(isset($user)) {
                    $this->userRepository->delete($user);
                    $this->userRoleRepository->delete($deleteUserRequest->getUser());
                }
                return $this;
            }
        }
        return $this;
    }
}