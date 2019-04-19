<?php

namespace App\Repository;

use App\Entity\UserRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRoleRepository extends ServiceEntityRepository
{

    /**
     * UserRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct(
            $registry,
            UserRole::class
        );
    }


    /**
     * @param UserRole $userRole
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(UserRole $userRole)
    {
        $this->_em->persist($userRole);
        $this->_em->flush();
    }

    public function delete($user)
    {
        $this->createNamedQuery('delete') ->setParameter('userId', $user)
            ->getResult();
    }

}