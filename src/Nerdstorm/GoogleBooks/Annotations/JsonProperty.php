<?php

namespace Nerdstorm\GoogleBooks\Annotations;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class JsonProperty
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type = 'string';

    public function __construct($options)
    {
        $this->name = $options['value'];

        if (isset($options['type'])) {
            $this->type = $options['type'];
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
     * Return the camel-cased name of the property
     *
     * @return string
     */
    function getRelatedMethodName()
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $this->name)));
    }
}