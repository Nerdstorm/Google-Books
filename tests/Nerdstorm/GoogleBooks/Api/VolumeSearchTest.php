<?php

namespace tests\Nerdstorm\GoogleBooks\Api;

use GuzzleHttp\Psr7\Response;
use Nerdstorm\GoogleBooks\Api\VolumesSearch;
use Nerdstorm\GoogleBooks\Enum\OrderByEnum;
use Nerdstorm\GoogleBooks\Enum\ProjectionEnum;
use Nerdstorm\GoogleBooks\Enum\PublicationTypeEnum;
use Nerdstorm\GoogleBooks\Enum\VolumeFilterEnum;
use Nerdstorm\GoogleBooks\Query\VolumeSearchQuery;
use tests\Nerdstorm\Config;

class VolumeSearchTest extends \PHPUnit_Framework_TestCase
{
    /** @var VolumeSearch */
    protected $volume_search;

    public function setup()
    {
        /** @var VolumesSearch volume_search */
        $this->volume_search = new VolumesSearch(
            Config::API_KEY,
            Config::guzzleOpts()
        );
    }

    public function testVolumesListSimpleQuery()
    {
        /** @var Query $query */
        $query = new VolumeSearchQuery('Systems analysis and design');

        /** @var Response $response */
        $response = $this->volume_search->volumesList($query);
        $this->assertEquals(200, $response->getStatusCode());

        $json = (string) $response->getBody();
        $data = json_decode($json, true);

        $this->assertEquals('application/json; charset=UTF-8', $response->getHeader('Content-Type')[0]);
        $this->assertEquals($data['kind'], 'books#volumes');

        if ($data['totalItems'] >= 40) {
            $this->assertCount(40, $data['items']);
        } else {
            $this->assertCount($data['totalItems'], $data['items']);
        }
    }

    public function testVolumesListDownloadableContent()
    {
        /** @var Query $query */
        $query = new VolumeSearchQuery('Systems analysis and design');

        /** @var Response $response */
        $response = $this->volume_search->volumesList($query, true, VolumeFilterEnum::FREE_EBOOKS());
        $this->assertEquals(200, $response->getStatusCode());

        $json = (string) $response->getBody();
        $data = json_decode($json, true);

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
        $response = $this->volume_search->volumesList($query, false, null, $lang_code);
        $this->assertEquals(200, $response->getStatusCode());

        $json = (string) $response->getBody();
        $data = json_decode($json, true);

        foreach ($data['items'] as $volume) {
            $this->assertArrayHasKey($volume['volumeInfo']['language'], $accepted_langs);
            $this->assertArrayNotHasKey($volume['volumeInfo']['language'], $unaccepted_langs);
        }
    }

    public function testVolumesListOrderBy()
    {
        /** @var Query $query */
        $query = new VolumeSearchQuery('Flowers');

        /** @var Response $response */
        $response = $this->volume_search->volumesList($query, true, null, null, 0, 5, OrderByEnum::NEWEST());
        $this->assertEquals(200, $response->getStatusCode());

        $json = (string) $response->getBody();
        $data = json_decode($json, true);

        $publish_dates = [];
        foreach ($data['items'] as $volume) {
            $datetime = new \DateTime($volume['volumeInfo']['publishedDate']);
            $publish_dates[] = $datetime->getTimestamp();
        }

        $ordered_dates = $publish_dates;

        // Sort high to low (most recently published first)
        rsort($ordered_dates, SORT_NUMERIC);

        foreach ($ordered_dates as $index => $date) {
            $this->assertEquals($date, $publish_dates[$index]);
        }
    }

    public function testVolumesListPrintType()
    {
        /** @var Query $query */
        $query = new VolumeSearchQuery('Flowers');

        /*
         * Filter Books
         */
        /** @var Response $response */
        $response = $this->volume_search->volumesList(
            $query, false, null, null, 0, 10, null, PublicationTypeEnum::BOOKS()
        );
        $this->assertEquals(200, $response->getStatusCode());

        $json = (string) $response->getBody();
        $data = json_decode($json, true);

        foreach ($data['items'] as $volume) {
            $this->assertEquals($volume['volumeInfo']['printType'], 'BOOK');
        }

        /*
         * Filter Magazines
         */
        /** @var Response $response */
        $response = $this->volume_search->volumesList(
            $query, false, null, null, 0, 10, null, PublicationTypeEnum::MAGAZINES()
        );
        $this->assertEquals(200, $response->getStatusCode());

        $json = (string) $response->getBody();
        $data = json_decode($json, true);

        foreach ($data['items'] as $volume) {
            $this->assertEquals($volume['volumeInfo']['printType'], 'MAGAZINE');
        }
    }

    public function testVolumeGetById()
    {
        // Volume: Systems Analysis and Design
        $volume_id = '2kjxBQAAQBAJ';

        /** @var Response $response */
        $response = $this->volume_search->volumeGet($volume_id);
        $this->assertEquals(200, $response->getStatusCode());

        $json = (string) $response->getBody();
        $data = json_decode($json, true);

        $this->assertEquals('application/json; charset=UTF-8', $response->getHeader('Content-Type')[0]);
        $this->assertEquals($data['kind'], 'books#volume');
        $this->assertEquals($data['id'], $volume_id);
    }

    public function testVolumeGetByIdAndProjection()
    {
        // Volume: Systems Analysis and Design
        $volume_id = '2kjxBQAAQBAJ';

        /** @var Response $response */
        // Projection: FULL
        $response = $this->volume_search->volumeGet($volume_id, ProjectionEnum::FULL());
        $this->assertEquals(200, $response->getStatusCode());

        $json = (string) $response->getBody();
        $data = json_decode($json, true);

        $this->assertEquals('application/json; charset=UTF-8', $response->getHeader('Content-Type')[0]);
        $this->assertEquals($data['kind'], 'books#volume');
        $this->assertEquals($data['id'], $volume_id);
        $this->assertArrayHasKey('pageCount', $data['volumeInfo']);
        $this->assertArrayHasKey('categories', $data['volumeInfo']);

        /** @var Response $response */
        // Projection: LITE
        $response = $this->volume_search->volumeGet($volume_id, ProjectionEnum::LITE());
        $this->assertEquals(200, $response->getStatusCode());

        $json = (string) $response->getBody();
        $data = json_decode($json, true);

        $this->assertEquals('application/json; charset=UTF-8', $response->getHeader('Content-Type')[0]);
        $this->assertEquals($data['kind'], 'books#volume');
        $this->assertEquals($data['id'], $volume_id);
        $this->assertArrayNotHasKey('pageCount', $data['volumeInfo']);
        $this->assertArrayNotHasKey('categories', $data['volumeInfo']);

    }
}