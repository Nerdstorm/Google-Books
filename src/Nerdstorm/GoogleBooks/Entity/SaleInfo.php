<?php

namespace Nerdstorm\GoogleBooks\Entity;

use Nerdstorm\GoogleBooks\Enum\SaleabilityEnum;

/**
 * Class VolumeInfo
 *
 * General volume information.
 */
class VolumeInfo
{
    /**
     * The two-letter ISO_3166-1 country code for which this sale information is valid.
     *
     * @var string
     */
    protected $country;

    /**
     * Whether or not this book is available for sale or offered for free in the Google eBookstore for the
     * country listed above. Possible values are
     *
     * @var SaleabilityEnum
     */
    protected $saleability;

    /**
     * Whether or not this volume is an eBook (can be added to the My eBooks shelf).
     *
     * @var bool
     */
    protected $is_book;

    /**
     * Suggested retail price. (in LITE projection)
     *
     * @var BookPrice
     */
    protected $list_price;

    /**
     * The actual selling price of the book. This is the same as the suggested retail or list
     * price unless there are offers or discounts on this volume. (in LITE projection).
     *
     * @var BookPrice
     */
    protected $retail_price;

    /**
     * URL to purchase this volume on the Google Books site. (in LITE projection).
     *
     * @var string
     */
    protected $buy_link;

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
     * @return VolumeInfo
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return SaleabilityEnum
     */
    public function getSaleability()
    {
        return $this->saleability;
    }

    /**
     * @param SaleabilityEnum $saleability
     *
     * @return VolumeInfo
     */
    public function setSaleability($saleability)
    {
        $this->saleability = $saleability;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsBook()
    {
        return $this->is_book;
    }

    /**
     * @param boolean $is_book
     *
     * @return VolumeInfo
     */
    public function setIsBook($is_book)
    {
        $this->is_book = $is_book;

        return $this;
    }

    /**
     * @return BookPrice
     */
    public function getListPrice()
    {
        return $this->list_price;
    }

    /**
     * @param BookPrice $list_price
     *
     * @return VolumeInfo
     */
    public function setListPrice($list_price)
    {
        $this->list_price = $list_price;

        return $this;
    }

    /**
     * @return BookPrice
     */
    public function getRetailPrice()
    {
        return $this->retail_price;
    }

    /**
     * @param BookPrice $retail_price
     *
     * @return VolumeInfo
     */
    public function setRetailPrice($retail_price)
    {
        $this->retail_price = $retail_price;

        return $this;
    }

    /**
     * @return string
     */
    public function getBuyLink()
    {
        return $this->buy_link;
    }

    /**
     * @param string $buy_link
     *
     * @return VolumeInfo
     */
    public function setBuyLink($buy_link)
    {
        $this->buy_link = $buy_link;

        return $this;
    }

}