<?php


namespace App\Repository;


use App\Entity\UserGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserGroupRespository  extends ServiceEntityRepository
{

    /**
     * UserGroupRespository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct(
            $registry,
            UserGroup::class
        );
    }

    public function isGroupAssigned(string $user,string $group)
    {
         $count=$this->count(['userId' => $user,'groupId' => $group]);
         print_r($count);
        return $count>0;
    }

    public function isGroupMapped(string $group)
    {
        $count=$this->count(['groupId' => $group]);
        print_r($count);
        return $count>0;
    }

    public function findUserGroup(string $user,string $group)
    {
        return $this->findOneBy(['userId' => $user,'groupId' => $group]);
    }

    public function save(UserGroup $userGroup)
    {
        $this->_em->persist($userGroup);
        $this->_em->flush();
    }

    public function delete(UserGroup $userGroup)
    {
        $this->_em->remove($userGroup);
        $this->_em->flush();
    }

}