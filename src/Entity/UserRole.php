<?php 
namespace App\Entity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\NamedQuery;
use Doctrine\ORM\Mapping\NamedQueries;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_role")
 */
class UserRole extends BaseEntity{

    /**
     * @ORM\Column(name="user_id",type="string", length=255)
     *
     */
    public $userId;

    /**
     * @ORM\Column(name="role_id",type="integer")
     */
    public $roleId;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * @param mixed $roleId
     */
    public function setRoleId($roleId): void
    {
        $this->roleId = $roleId;
    }



}