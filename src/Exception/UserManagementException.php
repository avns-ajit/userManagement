<?php


namespace App\Exception;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;


class UserManagementException extends HttpException implements ExceptionInterface
{

    public function __construct(string $message, int $code, Exception $previous = null)
    {
        parent::__construct($code, $message, $previous);
    }
}