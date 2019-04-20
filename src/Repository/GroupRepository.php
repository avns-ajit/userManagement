<?php


namespace App\Repository;


use App\Constant\UserManagementConstants;
use App\Entity\Group;
use App\Exception\UserManagementException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @param Group $group
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Group $group)
    {
        $this->_em->remove($group);
        $this->_em->flush();
    }


    /**
     * @param string $groupId
     * @return Group
     */
    public function checkGroup(string $groupId): Group
    {
        $group=$this->findOneBy(['groupId' => $groupId]);
        if(!isset($group))
            throw new UserManagementException(UserManagementConstants::GROUP_NOT_AVAILABLE,Response::HTTP_BAD_REQUEST);
        return $group;
    }
}