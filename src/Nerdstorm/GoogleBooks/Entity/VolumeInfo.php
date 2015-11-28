<?php

namespace Nerdstorm\GoogleBooks\Entity;

use Nerdstorm\GoogleBooks\Annotations\Definition as Annotations;

/**
 * Class VolumeInfo
 *
 * General volume information.
 */
class VolumeInfo implements EntityInterface
{

    /**
     * Volume title. (In LITE projection.)
     *
     * @var string
     * @Annotations\JsonProperty("title", type="string")
     */
    protected $title;

    /**
     * Volume subtitle. (In LITE projection.)
     *
     * @var string
     * @Annotations\JsonProperty("subtitle", type="string")
     */
    protected $subtitle;

    /**
     * The names of the authors and/or editors for this volume. (In LITE projection)
     *
     * @var string[]
     * @Annotations\JsonProperty("authors", type="array")
     */
    protected $authors;

    /**
     * Publisher of this volume. (In LITE projection.)
     *
     * @var string
     * @Annotations\JsonProperty("publisher", type="string")
     */
    protected $publisher;

    /**
     * Date of publication. (In LITE projection.)
     *
     * @var \DateTime
     * @Annotations\JsonProperty("publishedDate", type="datetime")
     */
    protected $published_date;

    /**
     * A synopsis of the volume. The text of the description is formatted in HTML and includes simple
     * formatting elements, such as b, i, and br tags. (in LITE projection)
     *
     * @var string
     */
    protected $description;

    /**
     * Industry standard identifiers for this volume.
     * Identifier type. Possible values are ISBN_10, ISBN_13, ISSN and OTHER.
     *
     * @var array
     */
    protected $industry_identifiers;

    /**
     * Total number of pages.
     *
     * @var int
     */
    protected $page_count;

    /**
     * Physical dimensions of this volume.
     *
     * @var VolumeDimensions
     */
    protected $dimensions;

    /**
     * Type of publication of this volume. Possible values are BOOK or MAGAZINE.
     *
     * @var string
     */
    protected $print_type;

    /**
     * A list of subject categories, such as "Fiction", "Suspense", etc.
     *
     * @var array
     */
    protected $categories;

    /**
     * The mean review rating for this volume. (min = 1.0, max = 5.0)
     *
     * @var double
     */
    protected $average_rating;

    /**
     * The number of review ratings for this volume.
     *
     * @var int
     */
    protected $ratings_count;

    /**
     * An identifier for the version of the volume content (text & images). (In LITE projection)
     *
     * @var string
     */
    protected $content_version;

    /**
     * A list of image links for all the sizes that are available. (in LITE projection)
     *
     * @var VolumeImageLinks
     * @Annotations\JsonProperty("imageLinks", type="object", className="Nerdstorm\GoogleBooks\Entity\VolumeImageLinks")
     */
    protected $image_links;

    /**
     * Best language for this volume (based on content). It is the two-letter ISO 639-1 code such as 'fr', 'en', etc.
     *
     * @var string
     */
    protected $language;

    /**
     * The main category to which this volume belongs.
     * It will be the category from the categories list returned below that has the highest weight.
     *
     * @var string
     */
    protected $main_category;

    /**
     * URL to preview this volume on the Google Books site.
     *
     * @var string
     */
    protected $preview_link;

    /**
     * @return string
     */
    public function getMainCategory()
    {
        return $this->main_category;
    }

    /**
     * @param string $main_category
     *
     * @return VolumeInfo
     */
    public function setMainCategory($main_category)
    {
        $this->main_category = $main_category;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return VolumeInfo
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param string $subtitle
     *
     * @return VolumeInfo
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * @param string[] $authors
     *
     * @return VolumeInfo
     */
    public function setAuthors($authors)
    {
        $this->authors = $authors;

        return $this;
    }

    /**
     * @return string
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * @param string $publisher
     *
     * @return VolumeInfo
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getPublishedDate()
    {
        return $this->published_date;
    }

    /**
     * @param DateTime $published_date
     *
     * @return VolumeInfo
     */
    public function setPublishedDate($published_date)
    {
        $this->published_date = $published_date;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return VolumeInfo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return array
     */
    public function getIndustryIdentifiers()
    {
        return $this->industry_identifiers;
    }

    /**
     * @param array $industry_identifiers
     *
     * @return VolumeInfo
     */
    public function setIndustryIdentifiers($industry_identifiers)
    {
        $this->industry_identifiers = $industry_identifiers;

        return $this;
    }

    /**
     * @return int
     */
    public function getPageCount()
    {
        return $this->page_count;
    }

    /**
     * @param int $page_count
     *
     * @return VolumeInfo
     */
    public function setPageCount($page_count)
    {
        $this->page_count = $page_count;

        return $this;
    }

    /**
     * @return VolumeDimensions
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * @param VolumeDimensions $dimensions
     *
     * @return VolumeInfo
     */
    public function setDimensions($dimensions)
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrintType()
    {
        return $this->print_type;
    }

    /**
     * @param string $print_type
     *
     * @return VolumeInfo
     */
    public function setPrintType($print_type)
    {
        $this->print_type = $print_type;

        return $this;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     *
     * @return VolumeInfo
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return float
     */
    public function getAverageRating()
    {
        return $this->average_rating;
    }

    /**
     * @param float $average_rating
     *
     * @return VolumeInfo
     */
    public function setAverageRating($average_rating)
    {
        $this->average_rating = $average_rating;

        return $this;
    }

    /**
     * @return int
     */
    public function getRatingsCount()
    {
        return $this->ratings_count;
    }

    /**
     * @param int $ratings_count
     *
     * @return VolumeInfo
     */
    public function setRatingsCount($ratings_count)
    {
        $this->ratings_count = $ratings_count;

        return $this;
    }

    /**
     * @return string
     */
    public function getContentVersion()
    {
        return $this->content_version;
    }

    /**
     * @param string $content_version
     *
     * @return VolumeInfo
     */
    public function setContentVersion($content_version)
    {
        $this->content_version = $content_version;

        return $this;
    }

    /**
     * @return VolumeImageLinks
     */
    public function getImageLinks()
    {
        return $this->image_links;
    }

    /**
     * @param VolumeImageLinks $image_links
     *
     * @return VolumeInfo
     */
    public function setImageLinks($image_links)
    {
        $this->image_links = $image_links;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     *
     * @return VolumeInfo
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function getPreviewLink()
    {
        return $this->preview_link;
    }

    /**
     * @param string $preview_link
     *
     * @return VolumeInfo
     */
    public function setPreviewLink($preview_link)
    {
        $this->preview_link = $preview_link;

        return $this;
    }

}