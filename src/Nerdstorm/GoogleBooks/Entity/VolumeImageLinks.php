<?php

namespace Nerdstorm\GoogleBooks\Entity;

use Nerdstorm\GoogleBooks\Annotations\Definition as Annotations;

/**
 * Class VolumeImageLinks
 *
 * A list of image links for all the sizes that are available. (in LITE projection)
 */
class VolumeImageLinks implements EntityInterface
{
    /**
     * Image link for thumbnail size (width of ~128 pixels). (in LITE projection)
     *
     * @var string
     * @Annotations\JsonProperty("thumbnail", type="string")
     */
    protected $thumbnail;

    /**
     * Image link for small size (width of ~300 pixels). (in LITE projection)
     *
     * @var string
     * @Annotations\JsonProperty("small", type="string")
     */
    protected $small;

    /**
     * Image link for medium size (width of ~575 pixels). (in LITE projection)
     *
     * @var string
     * @Annotations\JsonProperty("medium", type="string")
     */
    protected $medium;

    /**
     * Image link for large size (width of ~800 pixels). (in LITE projection)
     *
     * @var string
     * @Annotations\JsonProperty("large", type="string")
     */
    protected $large;

    /**
     * Image link for small thumbnail size (width of ~80 pixels). (in LITE projection)
     *
     * @var string
     * @Annotations\JsonProperty("smallThumbnail", type="string")
     */
    protected $small_thumbnail;

    /**
     * Image link for extra large size (width of ~1280 pixels). (in LITE projection)
     *
     * @var string
     * @Annotations\JsonProperty("extraLarge", type="string")
     */
    protected $extra_large;

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param string $thumbnail
     *
     * @return VolumeImageLinks
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * @return string
     */
    public function getSmall()
    {
        return $this->small;
    }

    /**
     * @param string $small
     *
     * @return VolumeImageLinks
     */
    public function setSmall($small)
    {
        $this->small = $small;

        return $this;
    }

    /**
     * @return string
     */
    public function getMedium()
    {
        return $this->medium;
    }

    /**
     * @param string $medium
     *
     * @return VolumeImageLinks
     */
    public function setMedium($medium)
    {
        $this->medium = $medium;

        return $this;
    }

    /**
     * @return string
     */
    public function getLarge()
    {
        return $this->large;
    }

    /**
     * @param string $large
     *
     * @return VolumeImageLinks
     */
    public function setLarge($large)
    {
        $this->large = $large;

        return $this;
    }

    /**
     * @return string
     */
    public function getSmallThumbnail()
    {
        return $this->small_thumbnail;
    }

    /**
     * @param string $small_thumbnail
     *
     * @return VolumeImageLinks
     */
    public function setSmallThumbnail($small_thumbnail)
    {
        $this->small_thumbnail = $small_thumbnail;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtraLarge()
    {
        return $this->extra_large;
    }

    /**
     * @param string $extra_large
     *
     * @return VolumeImageLinks
     */
    public function setExtraLarge($extra_large)
    {
        $this->extra_large = $extra_large;

        return $this;
    }

}