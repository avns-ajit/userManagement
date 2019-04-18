<?php 
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="role_permission")
 */
class RolePermission {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    /**
     * @ORM\Column(name="role_id",type="integer")
     */
    public $roleId;

    /**
     * @ORM\Column(name="permission_id",type="integer")
     */
    public $permissionId;

    /**
     * @ORM\Column(name="created_on",type="bigint")
     */
    public $createdOn;

    /**
     * @ORM\Column(name="updated_by",type="string", length=100)
     */
    public $updatedBy;

    /**
     * @ORM\Column(name="updated_on",type="bigint",nullable=true)
     */
    public $updatedOn;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * @return mixed
     */
    public function getPermissionId()
    {
        return $this->permissionId;
    }

    /**
     * @param mixed $permissionId
     */
    public function setPermissionId($permissionId): void
    {
        $this->permissionId = $permissionId;
    }

    /**
     * @return mixed
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param mixed $createdOn
     */
    public function setCreatedOn($createdOn): void
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return mixed
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param mixed $updatedBy
     */
    public function setUpdatedBy($updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return mixed
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @param mixed $updatedOn
     */
    public function setUpdatedOn($updatedOn): void
    {
        $this->updatedOn = $updatedOn;
    }


}