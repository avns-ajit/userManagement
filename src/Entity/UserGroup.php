<?php 
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="user_group")
 */
class UserGroup {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="int", length=100)
     */
    public $userId;

    /**
     * @ORM\Column(type="int", length=100)
     */
    public $groupId;

    /**
     * @ORM\Column(type="string", length=100)
     */
    public $createdBy;

    /**
     * @ORM\Column(type="string", length=100)
     */
    public $updatedBy;

    /**
     * @ORM\Column(type="bigint")
     */
    public $updatedTimestamp;
    

}