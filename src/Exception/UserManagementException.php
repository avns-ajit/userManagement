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

            default:
                $customCode=1000;
                $description="unknown";
                parent::__construct($code, $description,null,[],$customCode);
                break;

        }
        return;
    }
}