<?php 
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="Users")
 */
class User {
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
     * @ORM\Column(type="boolean")
     */
    public $isDeleted;

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
    
    //Getters and Setters
}