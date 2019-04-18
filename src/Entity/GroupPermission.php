<?php 
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="group_permission")
 */
class GroupPermission {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    /**
     * @ORM\Column(name="group_id",type="string", length=255)
     */
    public $groupId;

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
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @param mixed $groupId
     */
    public function setGroupId($groupId): void
    {
        $this->groupId = $groupId;
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