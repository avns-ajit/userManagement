<?php 
namespace App\Entity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\BaseEntity;
/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 *
 */
class  User extends BaseEntity {

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    public $name;

    /**
     * @ORM\Column(name="user_id",type="string", length=60)
     *
     */
    public $userId;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="user_role",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *      )
     */
    public $roles;

    /**
     * @ORM\ManyToMany(targetEntity="Group")
     * @ORM\JoinTable(name="user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="group_id")}
     *      )
     */
    public $groups;

    public function __construct() {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\Column(name="is_deleted",type="boolean")
     */
    public $isDeleted=false;

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
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param mixed $groups
     */
    public function setGroups($groups): void
    {
        $this->groups = $groups;
    }

    /**
     * @return mixed
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param mixed $isDeleted
     */
    public function setIsDeleted($isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }


}
