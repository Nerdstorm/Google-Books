<?php

namespace Nerdstorm\GoogleBooks\Api;

abstract class BooksBase
{
    /**
     * Google Books API endpoint
     */
    const ENDPOINT = 'https://www.googleapis.com/books/';

    /**
     * Google Books API version
     */
    const VERSION = 'v1';

    /**
     * API key received from Google
     *
     * @var string
     */
    protected $api_key = null;

    /**
     * Gets the API application key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * Sets the API application key.
     *
     * @param string $api_key the api key
     *
     * @return self
     */
    public function setApiKey($api_key)
    {
        $this->_api_key = $api_key;

        return $this;
    }

    /**
     * Call Google API endpoint using the parameters and method supplied.
     *
     * @param  string $method
     * @param  array  $params
     * @return string
     */
    protected function send($method, array $params)
    {
        $get_url = self::ENDPOINT . "?method=$method";
        $encoded_url_params = http_build_query(array_map('urlencode', $params));

        $json_response = file_get_contents($get_url . '&' . $encoded_url_params);
        return $json_response;
    }

}