<?php


namespace App\Repository;


use App\Constant\UserManagementConstants;
use App\Entity\UserGroup;
use App\Exception\UserManagementException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Response;

class UserGroupRepository  extends ServiceEntityRepository
{

    /**
     * UserGroupRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct(
            $registry,
            UserGroup::class
        );
    }

    /**
     * @param string $user
     * @param string $group
     */
    public function checkIfGroupAssigned(string $user,string $group)
    {
        $count=$this->count(['userId' => $user,'groupId' => $group]);
        if($count>0)
            throw new UserManagementException(UserManagementConstants::GROUP_ALREADY_ASSIGNED,Response::HTTP_FORBIDDEN);
    }

    /**
     * @param string $group
     */
    public function checkUsersInGroup(string $group)
    {
        $count=$this->count(['groupId' => $group]);
        if($count>0)
            throw new UserManagementException(UserManagementConstants::GROUP_NOT_EMPTY,Response::HTTP_FORBIDDEN);
    }

    /**
     * @param string $user
     * @param string $group
     * @return UserGroup
     */
    public function checkIfGroupHasUser(string $user,string $group): UserGroup
    {
         $userGroup=$this->findOneBy(['userId' => $user,'groupId' => $group]);
         if(!isset($userGroup))
             throw new UserManagementException(UserManagementConstants::GROUP_NOT_ASSIGNED,Response::HTTP_FORBIDDEN);
         return $userGroup;
    }

    /**
     * @param UserGroup $userGroup
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(UserGroup $userGroup)
    {
        $this->_em->persist($userGroup);
        $this->_em->flush();
    }

    /**
     * @param UserGroup $userGroup
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(UserGroup $userGroup)
    {
        $this->_em->remove($userGroup);
        $this->_em->flush();
    }

}