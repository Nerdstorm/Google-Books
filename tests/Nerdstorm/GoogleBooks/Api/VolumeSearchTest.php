<?php

namespace tests\Nerdstorm\GoogleBooks\Api;

use GuzzleHttp\Psr7\Response;
use Nerdstorm\GoogleBooks\Api\VolumesSearch;
use tests\Nerdstorm\Config;

class VolumeSearchTest extends \PHPUnit_Framework_TestCase
{
    /** @var VolumeSearch */
    protected $volume_search;

    const SEARCH_QUERY = 'Systems analysis and design';

    public function setup()
    {
        $this->volume_search = new VolumesSearch(
            Config::API_KEY,
            Config::guzzleOpts()
        );
    }

    public function testVolumesListSimpleQuery()
    {
        /** @var Response $response */
        $response = $this->volume_search->volumesList(self::SEARCH_QUERY);

        $json = (string) $response->getBody();
        $data = json_decode($json, true);


        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json; charset=UTF-8', $response->getHeader('Content-Type')[0]);
        $this->assertEquals($data['kind'], 'books#volumes');
        if ($data['totalItems'] >= 10) {
            $this->assertCount(10, $data['items']);
        } else {
            $this->assertCount($data['totalItems'], $data['items']);
        }
    }

    public function testVolumesListFilterQuery()
    {
        /** @var Response $response */
        $response = $this->volume_search->volumesList(self::SEARCH_QUERY);

        $json = (string) $response->getBody();
        $data = json_decode($json, true);


        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json; charset=UTF-8', $response->getHeader('Content-Type')[0]);
        $this->assertEquals($data['kind'], 'books#volumes');
        if ($data['totalItems'] >= 10) {
            $this->assertCount(10, $data['items']);
        } else {
            $this->assertCount($data['totalItems'], $data['items']);
        }
    }

}