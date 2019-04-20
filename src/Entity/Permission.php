<?php 
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\BaseEntity;
/**
 * @ORM\Entity
 * @ORM\Table(name="permission")
 */
class Permission extends BaseEntity {

    /**
     * @ORM\Column(type="string", length=255)
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