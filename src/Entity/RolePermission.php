<?php 
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\BaseEntity;
/**
 * @ORM\Entity
 * @ORM\Table(name="role_permission")
 */
class RolePermission extends BaseEntity {

    /**
     * @ORM\Column(name="role_id",type="integer")
     */
    public $roleId;

    /**
     * @ORM\Column(name="permission_id",type="integer")
     */
    public $permissionId;

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


}