<?php 
namespace App\Entity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\NamedQuery;
use Doctrine\ORM\Mapping\NamedQueries;
/**
 * @ORM\Entity
 * @ORM\Table(name="role")
 * @ORM\NamedQueries({
 *     @NamedQuery(name="getPermissions", query="select p from App\Entity\Role r, App\Entity\Permission p, App\Entity\RolePermission rp where r.id in(:role) and r.id=rp.roleId and rp.permissionId=p.id")
 * })
 */
class Role  extends BaseEntity{

    /**
     * @ORM\Column(type="string", length=100)
     */
    public $name;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

}