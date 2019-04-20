<?php 
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\BaseEntity;
/**
 * @ORM\Entity
 * @ORM\Table(name="group_permission")
 */
class GroupPermission extends BaseEntity {

    /**
     * @ORM\Column(name="group_id",type="string", length=255)
     */
    public $groupId;

    /**
     * @ORM\Column(name="permission_id",type="integer")
     */
    public $permissionId;

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


}