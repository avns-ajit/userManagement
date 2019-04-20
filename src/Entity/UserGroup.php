<?php 
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\BaseEntity;
/**
 * @ORM\Entity
 * @ORM\Table(name="user_group")
 */
class UserGroup extends BaseEntity {

    /**
     * @ORM\Column(name="user_id",type="string", length=255)
     */
    public $userId;

    /**
     * @ORM\Column(name="group_id",type="string", length=255)
     */
    public $groupId;

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

}