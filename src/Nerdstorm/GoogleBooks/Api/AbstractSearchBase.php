<?php

namespace Nerdstorm\GoogleBooks\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
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
     * Guzzle Http client object
     *
     * @var Client
     */
    protected $client = null;

    /**
     * Guzzle Client default configuration
     * @var array
     */
    protected $client_config = [];

    /**
     * @param       $api_key
     * @param array $config
     */
    public function __construct($api_key, array $config = [])
    {
        $config['base_uri'] = self::ENDPOINT . self::VERSION . '/';

        // Default query parameters for requests
        $this->client_config['request.options']['query'] = [
            'key' => $api_key
        ];

        // Override config values
        $this->client_config += $config;
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
        // Google Books API supported methods
        $accepted_methods = [
            'GET',
            'POST',
        ];

        if (null == $this->client) {
            $this->client = new Client($this->client_config);
        }

        if (!in_array(strtoupper($method), $accepted_methods)) {
            throw new \InvalidArgumentException('HTTP method is not valid for Google Books search endpoints.');
        }

        /** @var Response $response */
        $response = call_user_func([$this->client, $method], $api_func, $query);
        return $response;
    }

}