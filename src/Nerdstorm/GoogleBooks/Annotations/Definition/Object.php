<?php

namespace Nerdstorm\GoogleBooks\Annotations\Definition;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Object
{
    /**
     * @var string
     */
    protected $name;

    public function __construct($options)
    {
        $this->name = $options['value'];
    }

    /**
     * Get the name of the object JSON mapping
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}