<?php


namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Constant\UserManagementConstants;


class UserManagementException extends HttpException implements ExceptionInterface
{

    public function __construct(string $message, int $code)
    {
        $this->getExceptionDetails($message,$code);
    }

    private function getExceptionDetails($message,$code){

        switch ($message){
            case UserManagementConstants::ROLE_NOT_AVAILABLE:
                $customCode=1001;
                $description="Given Role is not present, Please check available roles";
                parent::__construct($code, $description,null,[],$customCode);
                break;

            case UserManagementConstants::NOT_AUTHORIZED:
                $customCode=1002;
                $description="Initiator not authorized to perform this action";
                parent::__construct($code, $description,null,[],$customCode);
                break;
            case UserManagementConstants::USER_NOT_AVAILABLE:
                $customCode=1003;
                $description="Given user is not present, Please check available users";
                parent::__construct($code, $description,null,[],$customCode);
                break;
            case UserManagementConstants::GROUP_NOT_EMPTY:
                $customCode=1004;
                $description="Given Group contains users,remove users from group to perform this action";
                parent::__construct($code, $description,null,[],$customCode);
                break;
            case UserManagementConstants::GROUP_NOT_AVAILABLE:
                $customCode=1005;
                $description="Given group is not present, Please check available groups";
                parent::__construct($code, $description,null,[],$customCode);
                break;
            case UserManagementConstants::INITIATOR_NOT_AVAILABLE:
                $customCode=1006;
                $description="Given request initiator is not present, Please check available users";
                parent::__construct($code, $description,null,[],$customCode);
                break;
            case UserManagementConstants::GROUP_NOT_ASSIGNED:
                $customCode=1007;
                $description="Given group is not assigned to user";
                parent::__construct($code, $description,null,[],$customCode);
                break;
            case UserManagementConstants::GROUP_ALREADY_ASSIGNED:
                $customCode=1085;
                $description="Given group is already assigned to user";
                parent::__construct($code, $description,null,[],$customCode);
                break;

            default:
                $customCode=1000;
                $description="unknown";
                parent::__construct($code, $description,null,[],$customCode);
                break;

        }
        return;
    }
}