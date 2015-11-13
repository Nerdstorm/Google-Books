<?php

namespace Nerdstorm\GoogleBooks\Annotations\Mapper;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Nerdstorm\GoogleBooks\Entity as Entity;

class AnnotationMapper
{
    const BASE_PATH        = __DIR__ . '/../../../../';
    const ENTITY_NAMESPACE = 'Nerdstorm\\GoogleBooks\\Entity\\';
    const CLASS_OBJECT     = 'Nerdstorm\\GoogleBooks\\Annotations\\Object';
    const CLASS_PROPERTY   = 'Nerdstorm\\GoogleBooks\\Annotations\\JsonProperty';

    /**
     * @var AnnotationReader
     */
    protected $reader;

    /**
     * Parsed entity classes list
     * @var Array
     */
    protected $entities;

    public function __construct()
    {
        // Load annotation classes
        AnnotationRegistry::registerAutoloadNamespace(
            'Nerdstorm\GoogleBooks\Annotations\Annotation',
            self::BASE_PATH
        );

        $this->reader = new AnnotationReader();

        // Load entities for annotation mapping
        $dir_iterator   = new \RecursiveDirectoryIterator(self::BASE_PATH . 'Nerdstorm/GoogleBooks/Entity/');
        $regex_iterator = new \RegexIterator($dir_iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        foreach ($regex_iterator as $entity_file) {
            $this->entities[] = self::ENTITY_NAMESPACE . substr(basename($entity_file[0]), 0, -4);
            include_once $entity_file[0];
        }

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

        return;

        switch ($json_object['kind']) {
            case 'books#volume':
                $mapped_object = new Volume();
                break;
        }

        $reflection_object = new \ReflectionObject($mapped_object);

        foreach ($reflection_object->getProperties() as $reflection_property) {

            /**
             * Fetch annotations from the annotation reader
             * @var Nerdstorm\GoogleBooks\Annotations\Annotation\JsonProperty $annotation
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