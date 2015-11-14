<?php

namespace Nerdstorm\GoogleBooks\Annotations\Mapper;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Nerdstorm\GoogleBooks\Entity as Entity;

class AnnotationMapper
{
    const BASE_PATH        = __DIR__ . '/../../../../';
    const ENTITY_NAMESPACE = '\\Nerdstorm\\GoogleBooks\\Entity\\';
    const CLASS_OBJECT     = 'Nerdstorm\GoogleBooks\Annotations\Object';
    const CLASS_PROPERTY   = 'Nerdstorm\GoogleBooks\Annotations\JsonProperty';

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
            'Nerdstorm\GoogleBooks\Annotations',
            self::BASE_PATH
        );

        $this->reader = new AnnotationReader();

//        // Load entities for annotation mapping
//        $dir_iterator   = new \RecursiveDirectoryIterator(self::BASE_PATH . 'Nerdstorm/GoogleBooks/Entity/');
//        $regex_iterator = new \RegexIterator($dir_iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);
//
//        foreach ($regex_iterator as $entity_file) {
//            $class_name        = self::ENTITY_NAMESPACE . substr(basename($entity_file[0]), 0, -4);
//            $class             = new $class_name();
//            $reflection_object = new \ReflectionObject($class);
//
//            /** @var Object $annotation */
//            $annotation = $this->reader->getClassAnnotations($reflection_object);
//            var_dump($annotation);
//        }
//
//        die;
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
        $mapped_object = null;

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

    protected function resolveEntity($entity_type)
    {
        switch ($entity_type) {
            case 'books#volume':
                $mapped_object = new Volume();
                break;
        }
    }
}