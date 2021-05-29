<?php

declare(strict_types=1);

namespace App\Event;

use App\Exception\ApplicationException;
use App\Exception\BusinessException;
use App\Exception\ValidationException;
use DomainException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class HTTPExceptionListener
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event) : void
    {
        /** @var Exception $error */
        $error = $event->getThrowable();
        $this->logger->critical($error->getMessage());
//        var_dump($error->getMessage());
        $response = $this->responseDomainException($error);

//        die();
//        switch (get_class($error)) {
//            case ApplicationException::class:
//                /** @var ApplicationException $error */
//                $response = $this->responseApplicationException($error);
//                break;
//            case DomainException::class:
//            case BusinessException::class:
//                /** @var DomainException $error */
//                $response = $this->responseDomainException($error);
//                break;
//            case ValidationException::class:
//                /** @var ValidationException $error */
//                $response = $this->responseValidationException($error);
//                break;
//            default:
//
//                break;
//        }

        if($response) {
            $response->headers->set('Content-Type', 'application/json');
            $event->setResponse($response);
        }
    }

    private function responseValidationException(ValidationException $exception): Response
    {
        return new Response($exception->handle(), $exception->getStatusCode());
    }

    private function responseApplicationException(ApplicationException $exception): Response
    {
        return new Response($exception->handle(), $exception->getCode());
    }

    private function responseDomainException(Exception $exception): Response
    {
        if($message = json_decode($exception->getMessage())) {}
        else { $message = $exception->getMessage(); }
        $message = ['errors'=> [
            'domain'=> $message,
//            'stackTrace' => $exception->getTrace()
        ]];
        return new Response(json_encode($message), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
