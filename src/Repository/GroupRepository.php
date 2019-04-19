<?php


namespace App\Repository;


use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\ORMException;

class GroupRepository extends ServiceEntityRepository
{

    /**
     * GroupRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct(
            $registry,
            Group::class
        );
    }

    /**
     * @param Group $group
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Group $group)
    {
        $this->_em->persist($group);
        $this->_em->flush();
    }

    public function delete($groupid)
    {
        $this->createNamedQuery('delete') ->setParameter('group', $groupid)
            ->getResult();
    }
}