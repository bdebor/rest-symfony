<?php

namespace AppBundle\Serializer;

use AppBundle\Annotation\Link;
use Doctrine\Common\Annotations\Reader;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\JsonSerializationVisitor;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Routing\RouterInterface;

class LinkSerializationSubscriber implements EventSubscriberInterface
{
    private $router;
    private $annotationReader;

    public function __construct(RouterInterface $router, Reader $annotationReader)
    {
        $this->router = $router;
        $this->annotationReader = $annotationReader;
    }

    public static function getSubscribedEvents()
    {
        return array(
            array(
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'format' => 'json',
                'class' => 'AppBundle\Entity\Programmer'
            )
        );
    }

    public function onPostSerialize(ObjectEvent $event) {
//        /** @var Programmer $programmer */
//        $programmer = $event->getObject();
        /** @var JsonSerializationVisitor $visitor */
        $visitor = $event->getVisitor();
//        $visitor->addData(
//            'uri',
//            $this->router->generate('api_programmers_show', [
//                'nickname' => $programmer->getNickname()
//            ])
//        );

        $object      = $event->getObject();
        $annotations = $this->annotationReader
            ->getClassAnnotations(new \ReflectionObject($object));
        $links       = array();

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Link) {
                $uri = $this->router->generate(
                    $annotation->route,
                    $this->resolveParams($annotation->params, $object)
                );
                $links[$annotation->name] = $uri;
            }
        }
        $visitor->addData('_links', $links);
    }

    private function resolveParams(array $params, $object)
    {
        return $params;
    }
}
