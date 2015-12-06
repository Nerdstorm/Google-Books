<?php

namespace Nerdstorm\GoogleBooks\Entity;

use Nerdstorm\GoogleBooks\Annotations\Definition as Annotations;

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
     * @var float
     * @Annotations\JsonProperty("height", type="float")
     */
    protected $height;

    /**
     * Width of this volume (in cm).
     *
     * @var float
     * @Annotations\JsonProperty("width", type="float")
     */
    protected $width;

    /**
     * Thickness of this volume (in cm).
     *
     * @var float
     * @Annotations\JsonProperty("thickness", type="float")
     */
    protected $thickness;

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param float $height
     *
     * @return VolumeDimensions
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return float
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param float $width
     *
     * @return VolumeDimensions
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return float
     */
    public function getThickness()
    {
        return $this->thickness;
    }

    /**
     * @param float $thickness
     *
     * @return VolumeDimensions
     */
    public function setThickness($thickness)
    {
        $this->thickness = $thickness;

        return $this;
    }

}