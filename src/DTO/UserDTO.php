<?php


namespace App\DTO;

use \JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;



final class UserDTO
{
    /**
     * @Type("string")
     * @Assert\NotNull(message="user name cannot be null")
     * @Assert\NotBlank(message="user name cannot be empty")
     */
    private $name;

    /**
     * @Type("string")
     * @Assert\NotNull(message="request initiator id cannot be null")
     * @Assert\NotBlank(message="request initiator id cannot be empty")
     */
    private $initiator;

    /**
     * @Type("string")
     * @Assert\NotNull(message="user id cannot be null")
     * @Assert\NotBlank(message="user id cannot be empty")
     */
    private $role;

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
    public function getInitiator()
    {
        return $this->initiator;
    }

    /**
     * @param mixed $initiator
     */
    public function setInitiator($initiator): void
    {
        $this->initiator = $initiator;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;
    }

}