<?php

namespace Nerdstorm\GoogleBooks\Query;

interface QueryInterface
{

    /**
     * Constructor with a base query is required.
     *
     * @param string $query
     */
    public function __construct($query);

    /**
     * Return the HTML encoded query string which can be embedded in a HTTP request.
     *
     * @return string
     */
    public function __toString();

}