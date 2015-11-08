<?php

namespace Nerdstorm\GoogleBooks\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Nerdstorm\GoogleBooks\Annotations\JsonProperty;
use Nerdstorm\GoogleBooks\Entity\Volume;

class AnnotationMapper
{
    const CLASS_OBJECT   = 'Nerdstorm\GoogleBooks\Annotations\Object';
    const CLASS_PROPERTY = 'Nerdstorm\GoogleBooks\Annotations\JsonProperty';

    /**
     * @var AnnotationReader
     */
    protected $reader;

    /**
     * @param array $paths
     */
    public function __construct($paths = [])
    {
        if (!$paths) {
            $paths = [
                ['Nerdstorm\GoogleBooks\Annotations', __DIR__ . '/../../../'],
            ];
        }

        foreach ($paths as $path) {
            AnnotationRegistry::registerAutoloadNamespace($path[0], $path[1]);
        }

        $this->reader = new AnnotationReader();
    }

    /**
     * @param array $json_object
     * @param null  $parent_object
     *
     * TODO:
     *      JSON objects with "kind" can be mapped via the switch, but inner JSON data mapping needs a more
     *      efficient way to implement as there could be recusion.
     * @return Volume|null
     */
    public function map(array $json_object, $parent_object = null)
    {
        $mapped_object = null;

        switch ($json_object['kind']) {
            case 'books#volume':
                $mapped_object = new Volume();
                break;
        }

        $reflection_object = new \ReflectionObject($mapped_object);

        foreach ($reflection_object->getProperties() as $reflection_property) {

            // Fetch annotations from the annotation reader
            /** @var JsonProperty $annotation */
            $annotation = $this->reader->getPropertyAnnotation($reflection_property, self::CLASS_PROPERTY);

            if (null !== $annotation) {
                $property_name = $annotation->getName();

                if (isset($json_object[$property_name])) {

                    // Try to convert the JSON value to the requested PHP type
                    $type  = $annotation->getType();
                    $value = $json_object[$property_name];

                    // Try to instantiate object types
                    if ('entity' == $type) {
                        $parent_object = clone $mapped_object;
                        $this->map($value, $parent_object);
                    } elseif (false === settype($value, $type)) {
                        throw new \RuntimeException(sprintf('Could not convert value to type "%s"', $value));
                    }

                    call_user_func(
                        [
                            $mapped_object,
                            'set' . $annotation->getRelatedMethodName(),
                        ],
                        $value
                    );
                }
            }
        }

        return $mapped_object;
    }
}