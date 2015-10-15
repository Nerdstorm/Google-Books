<?php

namespace tests\Nerdstorm\GoogleBooks\Api;

use GuzzleHttp\Psr7\Response;
use Nerdstorm\GoogleBooks\Api\VolumesSearch;
use Nerdstorm\GoogleBooks\Enum\VolumeFilterEnum;
use Nerdstorm\GoogleBooks\Query\VolumeSearchQuery;
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
        /** @var Query $query */
        $query = new VolumeSearchQuery(self::SEARCH_QUERY);

        /** @var Response $response */
        $response = $this->volume_search->volumesList($query);

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

    public function testVolumesListDownloadableContent()
    {
        /** @var Query $query */
        $query = new VolumeSearchQuery(self::SEARCH_QUERY);

        /** @var Response $response */
        $response = $this->volume_search->volumesList($query, true, VolumeFilterEnum::FREE_EBOOKS());

        $json = (string) $response->getBody();
        $data = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());

        $available_for_download = 0;
        foreach ($data['items'] as $volume) {
            if (true === $volume['accessInfo']['epub']['isAvailable']) {
                $available_for_download++;
            } elseif (true === $volume['accessInfo']['pdf']['isAvailable']) {
                $available_for_download++;
            }
        }

        $this->assertCount($available_for_download, $data['items']);
    }

    public function testVolumesListLanguageFiltering()
    {
        $lang_code = 'fr';

        // Languages that should be included in search results
        $accepted_langs = [
            'fr' => 'fr',
            'en' => 'en',
        ];

        // Languages that should not be included in search results
        $unaccepted_langs = [
            'ru' => 'ru',
            'es' => 'es',
        ];

        /** @var Query $query */
        $query = new VolumeSearchQuery('The Little Prince');

        /** @var Response $response */
        $response = $this->volume_search->volumesList($query, true, VolumeFilterEnum::FREE_EBOOKS(), $lang_code);

        $json = (string) $response->getBody();
        $data = json_decode($json, true);

        $this->assertEquals(200, $response->getStatusCode());
        foreach ($data['items'] as $volume) {
            $this->assertArrayHasKey($volume['volumeInfo']['language'], $accepted_langs);
            $this->assertArrayNotHasKey($volume['volumeInfo']['language'], $unaccepted_langs);
        }

    }

}