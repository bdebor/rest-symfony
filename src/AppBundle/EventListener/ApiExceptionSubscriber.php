<?php

namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use AppBundle\Api\ApiProblemException;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents() {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();
        if (!$e instanceof ApiProblemException) {
            return;
        }

        $apiProblem = $e->getApiProblem();
        $response = new JsonResponse(
            $apiProblem->toArray(),
            $apiProblem->getStatusCode()
        );
        $response->headers->set('Content-Type', 'application/problem+json');

        $event->setResponse($response);
    }
}
