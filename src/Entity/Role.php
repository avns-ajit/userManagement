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
 *     @NamedQuery(name="getPermissions", query="select p from App\Entity\Role r, App\Entity\Permission p, App\Entity\RolePermission rp where r.id in(:roles) and r.id=rp.roleId and rp.permissionId=p.id")
 * })
 */
class Role {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    /**
     * @ORM\Column(type="string", length=100)
     */
    public $name;

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