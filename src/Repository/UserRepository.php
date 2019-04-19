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
     * @return User
     */
    public function findByUser(string $userId): User
    {
        $user=$this->findOneBy(['userId' => $userId]);
        if(!isset($user))
            throw new UserManagementException(UserManagementConstants::USER_NOT_AVAILABLE,Response::HTTP_BAD_REQUEST);
        return $user;
    }

    /**
     * @param string $userId
     * @return User
     */
    public function findInitiator(string $userId): User
    {
        $user=$this->findOneBy(['userId' => $userId]);
        if(!isset($user))
            throw new UserManagementException(UserManagementConstants::INITIATOR_NOT_AVAILABLE,Response::HTTP_FORBIDDEN);
        return $user;
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
        $this->_em->getConnection()->beginTransaction();
        try {
            $this->_em->remove($user);
            $this->_em->createQuery('delete from App\Entity\UserRole ur where ur.userId in(:userId)')->setParameter('userId', $user->getUserId())
                ->getResult();
            $this->_em->createQuery('delete from App\Entity\UserGroup ug where ug.userId in(:userId)')->setParameter('userId', $user->getUserId())
                ->getResult();
            $this->_em->flush();
            $this->_em->getConnection()->commit();
        } catch (Exception $e) {
            $this->_em->getConnection()->rollBack();
            throw $e;
        }
    }

}