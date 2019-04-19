<?php


namespace App\DTO;

use \JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class DeleteGroupDTO
{

    /**
     * @Type("string")
     * @Assert\NotNull(message="group id cannot be null")
     * @Assert\NotBlank(message="group id cannot be empty")
     */
    private $group;

    /**
     * @Type("string")
     * @Assert\NotNull(message="request initiator id cannot be null")
     * @Assert\NotBlank(message="request initiator id cannot be empty")
     */
    private $initiator;

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group): void
    {
        $this->group = $group;
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