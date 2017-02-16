<?php

namespace AppBundle\EventListener;

use AppBundle\Api\ResponseFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use AppBundle\Api\ApiProblemException;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Api\ApiProblem;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    private $debug;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct($debug, ResponseFactory $responseFactory) {
        $this->debug = $debug;
        $this->responseFactory = $responseFactory;
    }

    public static function getSubscribedEvents() {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (strpos($event->getRequest()->getPathInfo(), '/api') !== 0) {
            return;
        }

        $e = $event->getException();
        $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;
        if ($statusCode == 500 && $this->debug){
            return;
        }

        if ($e instanceof ApiProblemException) {
            $apiProblem = $e->getApiProblem();
        } else {
            $apiProblem = new ApiProblem($statusCode);

            if ($e instanceof HttpExceptionInterface) {
                $apiProblem->set('detail', $e->getMessage());
            }
        }

        $response = $this->responseFactory->createResponse($apiProblem);

        $event->setResponse($response);
    }
}
