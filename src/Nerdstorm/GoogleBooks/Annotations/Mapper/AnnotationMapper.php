<?php

namespace Nerdstorm\GoogleBooks\Annotations\Mapper;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Nerdstorm\GoogleBooks\Annotations\Definition\JsonProperty;
use Nerdstorm\GoogleBooks\Entity as Entity;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

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

    /**
     * @var PropertyAccessor
     */
    protected $accessor;

    public function __construct()
    {
        // Load annotation classes
        AnnotationRegistry::registerAutoloadNamespace(
            'Nerdstorm\GoogleBooks\Annotations\Definition',
            self::BASE_PATH
        );

        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->reader   = new AnnotationReader();
        $this->mapClassAnnotations();

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
            $class_name = self::ENTITY_NAMESPACE . substr(basename($entity_file[0]), 0, -4);

            // Ignore interfaces
            if (Entity\EntityInterface::class == $class_name) {
                continue;
            }

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

    /**
     * @return array
     */
    public function treeRecursion($object, $data_tree)
    {
        $reflection = new \ReflectionObject($object);

        // Iterate through current object properties
        foreach ($reflection->getProperties() as $reflection_property) {

            /**
             * Fetch annotations from the annotation reader
             * @var JsonProperty $annotation
             */
            $annotation = $this->reader->getPropertyAnnotation($reflection_property, self::CLASS_PROPERTY);

            if (null == $annotation) {
                continue;
            }

            $property_name = $annotation->getName();

            if ($annotation->getType() == 'object') {
                $class_name = $annotation->getClassName();
                $child_object = new $class_name();
                $this->accessor->setValue($object, $property_name, $child_object);
                $this->treeRecursion($child_object, $data_tree);
                continue;
            }

            $this->accessor->setValue($object, $property_name, 'test');
        }

        return $object;
    }

    /**
     * @param string $kind
     *
     * @return false|mixed
     */
    public function resolveEntity($kind)
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
}