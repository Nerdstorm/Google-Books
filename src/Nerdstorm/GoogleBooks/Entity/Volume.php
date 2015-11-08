<?php

namespace Nerdstorm\GoogleBooks\Entity;

use Nerdstorm\GoogleBooks\Annotations;

/**
 * Class Volume
 *
 * A Volume represents information that Google Books hosts about a book or a magazine. It contains metadata,
 * such as title and author, as well as personalized data, such as whether or not it has been purchased.
 * (Volume fields that are available in LITE projection are noted below).
 *
 * @Annotations\Object("books#volume")
 */
class Volume
{
    /**
     * Unique identifier for a volume
     *
     * @var string
     * @Annotations\JsonProperty("id", type="string")
     */
    protected $id;

    /**
     * Opaque identifier for a specific version of a volume resource
     *
     * @var string
     * @Annotations\JsonProperty("etag", type="string")
     */
    protected $etag;

    /**
     * URL to this resource
     *
     * @var string
     * @Annotations\JsonProperty("selfLink", type="string")
     */
    protected $self_link;

    /**
     * General volume information
     *
     * @var VolumeInfo
     * @Annotations\JsonProperty("volumeInfo", type="object")
     */
    protected $volume_info;

    /**
     * Any information about a volume related to the eBookstore and/or purchaseability.
     * This information can depend on the country where the request originates from (i.e. books may
     * not be for sale in certain countries)
     *
     * @var SaleInfo
     */
    protected $sale_info;

    /**
     * Any information about a volume related to reading or obtaining that volume text.
     * This information can depend on country (books may be public domain in one country but not in another, e.g.).
     *
     * @var AccessInfo
     */
    protected $access_info;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return Volume
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return AccessInfo
     */
    public function getAccessInfo()
    {
        return $this->access_info;
    }

    /**
     * @param AccessInfo $access_info
     *
     * @return Volume
     */
    public function setAccessInfo($access_info)
    {
        $this->access_info = $access_info;

        return $this;
    }

    /**
     * @return string
     */
    public function getEtag()
    {
        return $this->etag;
    }

    /**
     * @param string $etag
     *
     * @return Volume
     */
    public function setEtag($etag)
    {
        $this->etag = $etag;

        return $this;
    }

    /**
     * @return string
     */
    public function getSelfLink()
    {
        return $this->self_link;
    }

    /**
     * @param string $self_link
     *
     * @return Volume
     */
    public function setSelfLink($self_link)
    {
        $this->self_link = $self_link;

        return $this;
    }

    /**
     * @return VolumeInfo
     */
    public function getVolumeInfo()
    {
        return $this->volume_info;
    }

    /**
     * @param VolumeInfo $volume_info
     *
     * @return Volume
     */
    public function setVolumeInfo($volume_info)
    {
        $this->volume_info = $volume_info;

        return $this;
    }

    /**
     * @return SaleInfo
     */
    public function getSaleInfo()
    {
        return $this->sale_info;
    }

    /**
     * @param SaleInfo $sale_info
     *
     * @return Volume
     */
    public function setSaleInfo($sale_info)
    {
        $this->sale_info = $sale_info;

        return $this;
    }

}