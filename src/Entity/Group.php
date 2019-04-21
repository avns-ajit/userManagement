<?php 
namespace App\Entity;
use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="`group`")
 */
class Group extends BaseEntity {

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $name;
    /**
     * @ORM\Column(name="group_id",type="string", length=60)
     */
    public $groupId;
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