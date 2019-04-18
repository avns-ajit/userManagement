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
     * @Assert\NotNull(message="user id cannot be null")
     * @Assert\NotBlank(message="user id cannot be empty")
     */
    private $user;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function __toString(){
        return $this->getName();
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }


}