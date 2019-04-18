<?php


namespace App\Repository;


use App\Entity\User;
use App\Entity\UserRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{

    /**
     * UserRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct(
            $registry,
            User::class
        );
    }

    /**
     * @param string $userId
     * @return UserRole
     */
    public function findByUserId(string $userId): UserRole
    {
        return $this->findOneBy(['id' => $userId]);
    }

    /**
     * @param User $user
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

}