<?php

namespace Nerdstorm\GoogleBooks\Annotations\Definition;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class JsonProperty
{
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
    protected class_name;

    public function __construct($options)
    {
        $this->name = $options['value'];

        if (isset($options['type'])) {
            $this->type = $options['type'];
        }

        if (isset($options['className'])) {
            $this->class_name = $options['className'];
        }
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

    /**
     * Return the camel-cased name of the property
     *
     * @return string
     */
    function getRelatedMethodName()
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $this->name)));
    }
}