<?php

namespace Nerdstorm\GoogleBooks\Annotations\Mapper;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Nerdstorm\GoogleBooks\Annotations\Definition\JsonProperty;
use Nerdstorm\GoogleBooks\Entity as Entity;

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
//
//    /**
//     * TODO:
//     *      JSON objects with "kind" can be mapped via the switch, but inner JSON data mapping needs a more
//     *      efficient way to implement as there could be recusion.
//     *
//     * @return Entity\EntityInterface|null
//     */
//    public function map(Entity\EntityInterface $entity, $json_key, $json_data)
//    {
//        $reflection_object = new \ReflectionObject($entity);
//
//        foreach ($reflection_object->getProperties() as $reflection_property) {
//
//            /**
//             * Fetch annotations from the annotation reader
//             * @var JsonProperty $annotation
//             */
//            $annotation = $this->reader->getPropertyAnnotation($reflection_property, self::CLASS_PROPERTY);
//
//            if (null !== $annotation) {
//                $property_name = $annotation->getName();
//
//                if (isset($json_object[$property_name])) {
//
//                    // Try to convert the JSON value to the requested PHP type
//                    $type  = $annotation->getType();
//                    $value = $json_object[$property_name];
//
//                    // Try to instantiate object types
//                    if ('entity' == $type) {
//                        $parent_object = clone $entity;
//                        $this->map($value, $parent_object);
//                    } elseif (false === settype($value, $type)) {
//                        throw new \RuntimeException(sprintf('Could not convert value to type "%s"', $value));
//                    }
//
//                    call_user_func(
//                        [
//                            $entity,
//                            'set' . $annotation->getRelatedMethodName(),
//                        ],
//                        $value
//                    );
//                }
//            }
//        }
//
//        return $entity;
//    }

    /**
     * @param array  $tree
     * @param string $child_stem
     *
     * @return array
     */
    public function treeRecursion(array $tree, $child_stem = null)
    {
        static $object;
        $reflection = null;

        foreach ($tree as $stem => $leaf) {

            // Recurse throught array leafs
            if (is_array($leaf)) {
                $this->treeRecursion($leaf, $stem);
                continue;
            }

            // Figure out the parent entity type
            if ($stem === 'kind') {
                $object = $this->resolveEntity($leaf);
                continue;
            }

            $reflection = new \ReflectionObject($object);

            // Iterate through current object properties
            foreach ($reflection->getProperties() as $reflection_property) {

                /**
                 * Fetch annotations from the annotation reader
                 * @var JsonProperty $annotation
                 */
                $annotation = $this->reader->getPropertyAnnotation($reflection_property, self::CLASS_PROPERTY);

                if (null !== $annotation) {
                    $property_name = $annotation->getName();

                    if (isset($leaf[$property_name])) {

                        // Try to convert the JSON value to the requested PHP type
                        $type  = $annotation->getType();
                        $value = $leaf[$property_name];

                        // Try to instantiate object types
                        if ('entity' == $type) {
                            call_user_func(
                                [
                                    $object,
                                    'set' . $annotation->getRelatedMethodName(),
                                ],
                                $value
                            );

                            $this->treeRecursion($value, $stem);

                            continue;

                        } elseif (false === settype($value, $type)) {
                            throw new \RuntimeException(sprintf('Could not convert value to type "%s"', $value));
                        }

                        call_user_func(
                            [
                                $object,
                                'set' . $annotation->getRelatedMethodName(),
                            ],
                            $value
                        );
                    }

                }
            }
        }

        return $object;
    }

    /**
     * @param string $kind
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
}