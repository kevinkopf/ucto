<?php

namespace App\Service;

use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class Serializer extends \Symfony\Component\Serializer\Serializer
{
    /**
     * Serializer constructor.
     * @throws AnnotationException
     */
    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        // copied from here:
        // https://symfony.com/doc/current/components/serializer.html#using-callbacks-to-serialize-properties-with-object-instances
        $dateCallback =
            function (
                $innerObject,
                $outerObject,
                string $attributeName,
                string $format = null,
                array $context = []
            ) {
                return $innerObject instanceof DateTime ? $innerObject->format(DateTime::ISO8601) : '';
            }
        ;

        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'taxableSupplyDate' => $dateCallback,
            ],
        ];

        parent::__construct(
            [ new ObjectNormalizer($classMetadataFactory, null, null, null, null, null, $defaultContext) ],
            [ new JsonEncoder() ]
        );
    }
}
