<?php


namespace App\DTO;

use \JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class DeleteGroupDTO
{

    /**
     * @Type("string")
     * @Assert\NotBlank(message="group cannot be empty")
     */
    private $group;

    /**
     * @Type("string")
     * @Assert\NotBlank(message="request initiator cannot be empty")
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