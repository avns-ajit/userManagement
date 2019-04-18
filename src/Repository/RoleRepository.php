<?php


namespace App\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RoleRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct(
            $registry,
            Role::class
        );
    }




$implodedRoles = implode(',', $roles);

$qb = $this->createQueryBuilder('u')
->select('u')
->where('u.username is not null')
->andWhere('u.roles IN (:roles)')
->setParameter('roles', $implodedRoles);
}