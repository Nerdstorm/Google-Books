<?php

namespace Nerdstorm\GoogleBooks\Annotations\Mapper;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Nerdstorm\GoogleBooks\Annotations\Definition\JsonProperty;
use Nerdstorm\GoogleBooks\Annotations\Definition\Object;
use Nerdstorm\GoogleBooks\Entity as Entity;
use Symfony\Component\Yaml\Exception\RuntimeException;

class AnnotationMapper
{
    const BASE_PATH        = __DIR__ . '/../../../../';
    const ENTITY_NAMESPACE = 'Nerdstorm\\GoogleBooks\\Entity\\';
    const CLASS_OBJECT     = 'Nerdstorm\GoogleBooks\Annotations\Definition\Object';
    const CLASS_PROPERTY   = 'Nerdstorm\GoogleBooks\Annotations\Definition\JsonProperty';

    /**
     * @var AnnotationReader
     */
    protected $reader;

    /**
     * Result type to entity class mappings
     * Ex: books#volume => Volume
     *
     * @var array
     */
    protected $entity_mappings;

    public function __construct()
    {
        // Load annotation classes
        AnnotationRegistry::registerAutoloadNamespace(
            'Nerdstorm\GoogleBooks\Annotations\Definition',
            self::BASE_PATH
        );

        $this->reader = new AnnotationReader();
        $this->mapClassAnnotations();
    }

    /**
     * @param array $json_object
     * @param null  $parent_object
     *
     * TODO:
     *      JSON objects with "kind" can be mapped via the switch, but inner JSON data mapping needs a more
     *      efficient way to implement as there could be recusion.
     *
     * @return Volume|null
     */
    public function map(array $json_object, $parent_object = null)
    {
        $mapped_object = $this->resolveEntity($json_object);

        $reflection_object = new \ReflectionObject($mapped_object);

        foreach ($reflection_object->getProperties() as $reflection_property) {

            /**
             * Fetch annotations from the annotation reader
             * @var JsonProperty $annotation
             */
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

    /**
     * @param array $json_object
     *
     * @return false|mixed
     */
    protected function resolveEntity($kind)
    {
        if (!$kind) {
            return false;
        }

        if (!isset($this->entity_mappings[$kind])) {
            throw new \RuntimeException('JSON object kind ' . $kind . ' not defined within entity annotations');
        }

        $class_name = $this->entity_mappings[$kind];
        return new $class_name();
    }

    /**
     * Map entity class names to their JSON object type (kind).
     */
    protected function mapClassAnnotations()
    {
        // Load entities for annotation mappings
        $dir_iterator   = new \RecursiveDirectoryIterator(self::BASE_PATH . 'Nerdstorm/GoogleBooks/Entity/');
        $regex_iterator = new \RegexIterator($dir_iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        // Map class annotations
        foreach ($regex_iterator as $entity_file) {
            $class_name        = self::ENTITY_NAMESPACE . substr(basename($entity_file[0]), 0, -4);
            $class             = new $class_name();
            $reflection_object = new \ReflectionObject($class);

            /** @var Object $annotation */
            $annotations = $this->reader->getClassAnnotations($reflection_object);

            if (!$annotations) {
                continue;
            }

            $this->entity_mappings[$annotations[0]->getName()] = $class_name;
        }
    }
}