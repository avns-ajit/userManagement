<?php

namespace App\Repository;

use App\Entity\UserRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRoleRespository extends ServiceEntityRepository
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
     * @param string $userId
     * @return UserRole
     */
    public function findByUser(string $user): UserRole
    {
        return $this->findOneBy(['userId' => $user]);
    }
}