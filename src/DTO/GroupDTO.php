<?php


namespace App\DTO;


use \JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class GroupDTO
{

    /**
     * @Type("string")
     * @Assert\NotNull(message="group name cannot be null")
     * @Assert\NotBlank(message="group name cannot be empty")
     */
    private $name;

    /**
     * @Type("string")
     * @Assert\NotNull(message="request initiator id cannot be null")
     * @Assert\NotBlank(message="request initiator id cannot be empty")
     */
    private $initiator;

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



}