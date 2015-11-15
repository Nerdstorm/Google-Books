<?php

namespace Nerdstorm\GoogleBooks\Entity;

/**
 * Class VolumeDimensions
 *
 * Physical dimensions of this volume.
 */
class VolumeDimensions implements EntityInterface
{
    /**
     * Height or length of this volume (in cm).
     *
     * @var int
     */
    protected $height;

    /**
     * Width of this volume (in cm).
     *
     * @var int
     */
    protected $weight;

    /**
     * Thickness of this volume (in cm).
     *
     * @var int
     */
    protected $thickness;

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     *
     * @return VolumeDimensions
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     *
     * @return VolumeDimensions
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return int
     */
    public function getThickness()
    {
        return $this->thickness;
    }

    /**
     * @param int $thickness
     *
     * @return VolumeDimensions
     */
    public function setThickness($thickness)
    {
        $this->thickness = $thickness;

        return $this;
    }

}