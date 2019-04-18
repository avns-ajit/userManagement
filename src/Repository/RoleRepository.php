<?php


namespace App\Repository;


use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use mysql_xdevapi\Collection;
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
    public function findPermissionsForRoles(array $roles): array
    {
        $implodedRoles = implode(',', $roles);
        $qb = $this->createQueryBuilder('a');
        return  $qb
            ->select('a')
            ->Where('a.id IN (:roles)')
            ->setParameter('roles', $implodedRoles)
            ->getQuery()
            ->getResult();
    }

    public function findByRole(string $roleIds): Role
    {
        return $this->findOneBy(['id' => $roleIds]);
    }

}