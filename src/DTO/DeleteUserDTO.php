<?php


namespace App\DTO;

use \JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
class DeleteUserDTO
{

    /**
     * @Type("string")
     * @Assert\NotBlank(message="user cannot be empty")
     */
    private $user;

    /**
     * @Type("string")
     * @Assert\NotBlank(message="request initiator cannot be empty")
     */
    private $initiator;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
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