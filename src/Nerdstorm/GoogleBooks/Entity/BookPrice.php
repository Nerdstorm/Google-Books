<?php

namespace Nerdstorm\GoogleBooks\Entity;

/**
 * Class BookPrice
 *
 * Suggested retail price and list price of books can be represented using a BookPrice object.
 */
class BookPrice
{
    /**
     * Amount in the currency listed below. (In LITE projection.)
     *
     * @var double
     */
    protected $amount;

    /**
     * An ISO 4217, three-letter currency code. (In LITE projection.)
     *
     * @var string
     */
    protected $currency_code;

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return BookPrice
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currency_code;
    }

    /**
     * @param string $currency_code
     *
     * @return BookPrice
     */
    public function setCurrencyCode($currency_code)
    {
        $this->currency_code = $currency_code;

        return $this;
    }
}