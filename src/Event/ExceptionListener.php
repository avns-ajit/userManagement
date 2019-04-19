<?php


namespace App\Event;


use App\Exception\ExceptionInterface;
use App\Response\BaseResponse;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;


class ExceptionListener
{

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$event->getException() instanceof ExceptionInterface) {
            return;
        }
        $output = ['message' => $event->getException()->getMessage() ];
        $response= new Response(json_encode($output));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode($event->getException()->getStatusCode());
        $event->setResponse($response);

    }
}