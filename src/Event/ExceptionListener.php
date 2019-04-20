<?php


namespace App\Event;


use App\Exception\ExceptionInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;


class ExceptionListener
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$event->getException() instanceof ExceptionInterface) {
            return;
        }
        $output = ['message' => $event->getException()->getMessage(),'code' => $event->getException()->getCode() ];
        $statusCode=$event->getException()->getStatusCode();
        $response= new Response(json_encode($output));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode($statusCode);
        $event->setResponse($response);
        $this->logger->error($event->getException());

    }
}