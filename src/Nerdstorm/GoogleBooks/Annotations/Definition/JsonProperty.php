<?php

namespace Nerdstorm\GoogleBooks\Annotations\Definition;

use Nerdstorm\GoogleBooks\Annotations\Exception\InvalidPropertyTypeException;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class JsonProperty
{
    /**
     * Scalar and none scalar property types supported
     */
    const TYPE_ENUM        = 'enum';
    const TYPE_OBJECT      = 'object';
    const TYPE_OBJECTARRAY = 'object[]';
    const TYPE_ARRAY       = 'array';
    const TYPE_INT         = 'int';
    const TYPE_STRING      = 'string';
    const TYPE_BOOL        = 'bool';
    const TYPE_DATETIME    = 'datetime';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type = 'string';

    /**
     * @var string
     */
    protected $class_name;

    public function __construct($options)
    {
        $this->name = $options['value'];

        if ($this->isTypeValid($options['type'])) {
            $this->type = $options['type'];
        } else {
            throw new InvalidPropertyTypeException("Property '{$this->type}' is invalid");
        }

        if (isset($options['className'])) {
            $this->class_name = $options['className'];
        }
    }

    /**
     * Determines if the provided type is supported.
     *
     * @param string $type
     *
     * @return bool
     */
    public function isTypeValid($type)
    {
        $reflect         = new \ReflectionClass(get_class($this));
        $supported_types = $reflect->getConstants();

        if (in_array($type, $supported_types)) {
            return true;
        }

        return false;
    }

    /**
     * Get the name of the JSON property mapping
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the data type of the JSON property.
     * Return value would be static type or 'entity' for object mappings
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the class name set in the property
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->class_name;
    }
}