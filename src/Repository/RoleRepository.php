<?php


namespace App\Repository;


use App\Constant\UserManagementConstants;
use App\Entity\Role;
use App\Entity\User;
use App\Exception\UserManagementException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Response;

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
     * @return mixed
     */
    public function findPermissionsForRoles(array $roles)
    {
        $implodedRoles = implode(',', $roles);
        return $this->createNamedQuery('getPermissions') ->setParameter('role', $implodedRoles)
            ->getResult();
    }

    /**
     * @param string $roleIds
     * @return Role
     */
    public function findByRole(string $roleIds): Role
    {
        return $this->findOneBy(['id' => $roleIds]);
    }

    /**
     * @param string $roleName
     * @return Role
     */
    public function checkRole(string $roleName): Role
    {
        $role =$this->findOneBy(['name' => $roleName]);
        if(!isset($role))
            throw new UserManagementException(UserManagementConstants::ROLE_NOT_AVAILABLE,Response::HTTP_BAD_REQUEST);
        return $role;
    }

}