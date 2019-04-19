<?php


namespace App\Repository;


use App\Constant\UserManagementConstants;
use App\Entity\User;
use App\Exception\UserManagementException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Response;

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
    public function findByUser(string $userId): User
    {
        $user=$this->findOneBy(['userId' => $userId]);
        if(!isset($user))
            throw new UserManagementException(UserManagementConstants::NOT_AUTHORIZED,Response::HTTP_FORBIDDEN);
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

    public function delete(User $user)
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }

}