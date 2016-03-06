<?php

namespace Nerdstorm\GoogleBooks\Entity;

use Nerdstorm\GoogleBooks\Annotations\Definition as Annotations;
use Nerdstorm\GoogleBooks\Enum\AccessViewStatusEnum;
use Nerdstorm\GoogleBooks\Enum\ViewabilityEnum;

/**
 * Class AccessInfo
 *
 * Any information about a volume related to reading or obtaining that volume text.
 * This information can depend on country (books may be public domain in one country but not in another, e.g.).
 */
class AccessInfo implements EntityInterface
{
    /**
     * The two-letter ISO_3166-1 country code for which this access information is valid. (In LITE projection.)
     *
     * @var string
     * @Annotations\JsonProperty("country", type="string")
     */
    protected $country;

    /**
     * The read access of a volume. Possible values are PARTIAL, ALL_PAGES, NO_PAGES or UNKNOWN.
     * This value depends on the country listed above. A value of PARTIAL means that the publisher has allowed
     * some portion of the volume to be viewed publicly, without purchase. This can apply to eBooks as
     * well as non-eBooks. Public domain books will always have a value of ALL_PAGES.
     *
     * @var string
     * @Annotations\JsonProperty("viewability", type="enum", className="Nerdstorm\GoogleBooks\Enum\ViewabilityEnum")
     */
    protected $viewability;

    /**
     * Book volume has a EPUB version
     *
     * @var bool
     * @Annotations\JsonProperty("epub", type="array")
     */
    protected $has_epub;

    /**
     * Book volume has a PDF version
     *
     * @var bool
     * @Annotations\JsonProperty("pdf", type="array")
     */
    protected $has_pdf;

    /**
     * Combines the access and viewability of this volume into a single status field for this user.
     * Values can be FULL_PURCHASED, FULL_PUBLIC_DOMAIN, SAMPLE or NONE. (In LITE projection.)
     *
     * @var AccessViewStatusEnum
     * @Annotations\JsonProperty("accessViewStatus", type="enum", className="Nerdstorm\GoogleBooks\Enum\AccessViewStatusEnum")
     */
    protected $access_view_status;

    /**
     * Whether this volume can be embedded in a viewport using the Embedded Viewer API.
     *
     * @var bool
     * @Annotations\JsonProperty("embeddable", type="bool")
     */
    protected $embeddable;

    /**
     * URL to view information about this volume on the Google Books site. (In LITE projection)
     *
     * @var string
     * @Annotations\JsonProperty("publicDomain", type="string")
     */
    protected $public_domain;

    /**
     * URL to read this volume on the Google Books site. Link will not allow users to read non-viewable volumes.
     *
     * @var string
     * @Annotations\JsonProperty("webReaderLink", type="string")
     */
    protected $web_reader_link;

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return AccessInfo
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return ViewabilityEnum
     */
    public function getViewability()
    {
        return $this->viewability;
    }

    /**
     * @param ViewabilityEnum $viewability
     *
     * @return AccessInfo
     */
    public function setViewability($viewability)
    {
        $this->viewability = $viewability;

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasEpub()
    {
        return $this->has_epub;
    }

    /**
     * @param array $has_epub
     *
     * @return AccessInfo
     */
    public function setHasEpub(array $has_epub)
    {
        $this->has_epub = (bool) $has_epub['isAvailable'];

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasPdf()
    {
        return $this->has_pdf;
    }

    /**
     * @param array $has_pdf
     *
     * @return AccessInfo
     */
    public function setHasPdf(array $has_pdf)
    {
        $this->has_pdf = (bool) $has_pdf['isAvailable'];

        return $this;
    }

    /**
     * @return AccessViewStatusEnum
     */
    public function getAccessViewStatus()
    {
        return $this->access_view_status;
    }

    /**
     * @param AccessViewStatusEnum $access_view_status
     *
     * @return AccessInfo
     */
    public function setAccessViewStatus($access_view_status)
    {
        $this->access_view_status = $access_view_status;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEmbeddable()
    {
        return $this->embeddable;
    }

    /**
     * @param boolean $embeddable
     *
     * @return AccessInfo
     */
    public function setEmbeddable($embeddable)
    {
        $this->embeddable = $embeddable;

        return $this;
    }

    /**
     * @return string
     */
    public function getPublicDomain()
    {
        return $this->public_domain;
    }

    /**
     * @param string $public_domain
     *
     * @return AccessInfo
     */
    public function setPublicDomain($public_domain)
    {
        $this->public_domain = $public_domain;

        return $this;
    }

    /**
     * @return string
     */
    public function getWebReaderLink()
    {
        return $this->web_reader_link;
    }

    /**
     * @param string $web_reader_link
     *
     * @return AccessInfo
     */
    public function setWebReaderLink($web_reader_link)
    {
        $this->web_reader_link = $web_reader_link;

        return $this;
    }

}