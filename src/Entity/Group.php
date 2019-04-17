<?php 
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="group")
 */
class Group {
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

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy): void
    {
        $this->createdBy = $createdBy;
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
    public function getUpdatedTimestamp()
    {
        return $this->updatedTimestamp;
    }

    /**
     * @param mixed $updatedTimestamp
     */
    public function setUpdatedTimestamp($updatedTimestamp): void
    {
        $this->updatedTimestamp = $updatedTimestamp;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    

}