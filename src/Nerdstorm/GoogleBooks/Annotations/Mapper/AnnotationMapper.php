<?php

namespace Nerdstorm\GoogleBooks\Annotations\Mapper;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Nerdstorm\GoogleBooks\Annotations\Definition\JsonProperty;
use Nerdstorm\GoogleBooks\Entity as Entity;
use Nerdstorm\GoogleBooks\Exception\InvalidJsonException;
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
     * Map JSON data coming from GoogleBooks API to entities. This is a recursive function
     * and nested data arrays will be mapped by automatically initialising child entities and enums
     * required.
     *
     * @param Entity\EntityInterface $object
     * @param array                  $data_tree
     *
     * @return Entity\EntityInterface
     */
    protected function map(Entity\EntityInterface $object, $data_tree)
    {
        $reflection = new \ReflectionObject($object);

        // Iterate through current object properties
        foreach ($reflection->getProperties() as $reflection_property) {

            /**
             * Fetch annotations from the annotation reader
             * @var JsonProperty $annotation
             */
            $annotation = $this->reader->getPropertyAnnotation($reflection_property, self::CLASS_PROPERTY);

            // Ignore on empty annotation
            if (null == $annotation) {
                continue;
            }

            $tree_property_name  = $annotation->getName();
            $class_property_name = $reflection_property->getName();

            // Ignore and continue when no data found for property being set
            if (!isset($data_tree[$tree_property_name])) {
                continue;
            }

            // Attach child objects to tree
            switch ($annotation->getType()) {
                case JsonProperty::TYPE_OBJECT:
                    $class_name   = $annotation->getClassName();
                    $child_object = new $class_name();
                    $this->accessor->setValue($object, $class_property_name, $child_object);
                    $sub_tree = $this->accessor->getValue($data_tree, "[$tree_property_name]");

                    // Recall the function for the child object
                    $this->map($child_object, $sub_tree);
                    break;

                case JsonProperty::TYPE_OBJECTARRAY:
                    $object_array = [];
                    $class_name   = $annotation->getClassName();
                    $child_object = new $class_name();
                    $sub_trees = $this->accessor->getValue($data_tree, "[$tree_property_name]");

                    // Recall the map function to instantiate child objects
                    foreach ($sub_trees as $k => $sub_tree) {
                        $object_array[$k] = clone $this->map($child_object, $sub_tree);
                    }

                    $this->accessor->setValue($object, $class_property_name, $object_array);
                    break;

                case JsonProperty::TYPE_ENUM:
                    $class_name = $annotation->getClassName();
                    $value      = $class_name::memberByValue($data_tree[$tree_property_name]);
                    $this->accessor->setValue($object, $class_property_name, $value);
                    break;

                case JsonProperty::TYPE_ARRAY:
                    $sub_tree = $this->accessor->getValue($data_tree, "[$tree_property_name]");
                    $this->accessor->setValue($object, $class_property_name, $sub_tree);
                    break;

                case JsonProperty::TYPE_DATETIME:
                    $datetime_string = preg_replace(
                        '/[^0-9\-]/',
                        '',
                        $this->accessor->getValue($data_tree, "[$tree_property_name]")
                    );

                    // Try if the date time is parse-able if not set to null
                    try {
                        $datetime = new \DateTime($datetime_string);
                    } catch (\Exception $e) {
                        $datetime = null;
                    }

                    $this->accessor->setValue($object, $class_property_name, $datetime);
                    break;

                case JsonProperty::TYPE_STRING:
                    $this->accessor->setValue($object, $class_property_name, (string) $data_tree[$tree_property_name]);
                    break;

                case JsonProperty::TYPE_BOOL:
                    $this->accessor->setValue($object, $class_property_name, (bool) $data_tree[$tree_property_name]);
                    break;

                case JsonProperty::TYPE_INT:
                    $this->accessor->setValue($object, $class_property_name, (int) $data_tree[$tree_property_name]);
                    break;

                case JsonProperty::TYPE_FLOAT:
                    $this->accessor->setValue($object, $class_property_name, (float) $data_tree[$tree_property_name]);
                    break;
            }
        }

        return $object;
    }

    /**
     * @param array $json_data
     *
     * @return Entity\EntityInterface
     * @throws InvalidJsonException
     */
    public function resolveEntity(array $json_data)
    {
        if (empty($json_data['kind'])) {
            throw new InvalidJsonException();
        }

        $kind = $json_data['kind'];
        if (!isset($this->entity_mappings[$kind])) {
            throw new \RuntimeException('JSON object kind "' . $kind . '" not defined within entity annotations');
        }

        $class_name = $this->entity_mappings[$kind];
        return $this->map(new $class_name(), $json_data);
    }
}