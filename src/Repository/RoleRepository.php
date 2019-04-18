<?php


namespace App\Repository;


use App\Entity\Role;
use App\Entity\User;
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

    /**
     * @param array $roles
     * @return Role
     */
    public function findPermissionsForRoles(array $roles)
    {
        $implodedRoles = implode(',', $roles);
        return $this->createNamedQuery('getPermissions') ->setParameter('roles', $implodedRoles)
            ->getResult();
    }

    public function findByRole(string $roleIds): Role
    {
        return $this->findOneBy(['id' => $roleIds]);
    }

    public function findByName(string $roleName): Role
    {
        return $this->findOneBy(['name' => $roleName]);
    }

}