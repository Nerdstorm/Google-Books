<?php

namespace Nerdstorm\GoogleBooks\Api;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractSearchBase
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
     * Guzzle Http client object
     *
     * @var Client
     */
    protected $client = null;

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
     * @param  string $api_func
     * @param  array  $query
     *
     * @return ResponseInterface
     */
    protected function send($method, $api_func, array $query)
    {
        $params = [];

        $accepted_methods = [
            'GET',
            'POST',
        ];

        if (!in_array(strtoupper($method), $accepted_methods)) {
            throw new \InvalidArgumentException('HTTP method is not valid for Google Books search endpoints.');
        }

        if (null === $this->client) {
            $params['base_uri'] = self::ENDPOINT . self::VERSION;
            $this->client = new Client($params);
        }

        $response = call_user_func([$this->client, $method], $api_func, $query);

        return $response;
    }

}