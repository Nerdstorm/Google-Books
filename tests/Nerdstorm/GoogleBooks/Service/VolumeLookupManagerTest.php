<?php

namespace tests\Nerdstorm\GoogleBooks\Service;

use GuzzleHttp\Psr7\Response;
use Nerdstorm\GoogleBooks\Api\VolumesSearch;
use Nerdstorm\GoogleBooks\Entity\Volumes;
use Nerdstorm\GoogleBooks\Query\VolumeSearchQuery;
use Nerdstorm\GoogleBooks\Service\VolumeLookupManager;
use tests\Nerdstorm\Config;
use tests\Nerdstorm\GoogleBooks\Annotations\Mapper\AnnotationMapperTest;

class VolumeLookupManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var VolumeLookupManager */
    protected $volume_lookup_manager;

    /** @var AnnotationMapperTest */
    protected $annotation_mapper_test;

    /** @var VolumesSearch */
    protected $volume_search;

    public function setup()
    {
        /** @var VolumesSearch volume_search */
        $this->volume_search = new VolumesSearch(
            Config::API_KEY,
            Config::guzzleOpts()
        );

        /** @var VolumeLookupManager volume_lookup_manager */
        $this->volume_lookup_manager  = new VolumeLookupManager($this->volume_search);
        $this->annotation_mapper_test = new AnnotationMapperTest();
    }

    /**
     * Helper function to call the Google Books API directly and return results.
     * This is used for tests written for the VolumeLookupManager.
     *
     * @param VolumeSearchQuery $query
     */
    private function callApi(VolumeSearchQuery $query)
    {
        /** @var Response $response */
        $response = $this->volume_search->volumesList($query);
        $this->assertEquals(200, $response->getStatusCode());
        $json = (string) $response->getBody();

        return json_decode($json, true);
    }

    public function testLookupFn()
    {
        $query = new VolumeSearchQuery();
        $query->setTitle('Flowers');

        /**
         * Sub Test 1
         * -----------
         * Run a basic search and check how many items in the result.
         *
         */
        $results = $this->volume_lookup_manager->lookup($query, 0, 10);
        $this->assertEquals(10, $results->getTotalItems());
        $this->assertCount(10, $results->getItems());

        $volumes = $results->getItems();
        $volume_5 = $volumes[5];

        /**
         * Sub Test 2
         * -----------
         * Run a basic search similar to above and get results starting from 5th result
         *
         */
        $results = $this->volume_lookup_manager->lookup($query, 5, 10);
        $this->assertEquals(10, $results->getTotalItems());
        $this->assertCount(10, $results->getItems());

        /**
         * Sub Test 3
         * -----------
         * Compare the 5th volume of the "Sub Test 1" with the 0th Volume of the "Sub Test 2".
         * This proves cursor movement within the result set .
         */
        $volumes = $results->getItems();
        $this->assertEquals($volume_5->getId(), $volumes[0]->getId());

        /**
         * Sub Test 4
         * -----------
         * This is similar to above tests but uses a bigger result set.
         */
        $results = $this->volume_lookup_manager->lookup($query, 100, 200);
        $this->assertEquals(200, $results->getTotalItems());
        $this->assertCount(200, $results->getItems());

        $volumes = $results->getItems();
        $volume_180th = $volumes[180];

        $results = $this->volume_lookup_manager->lookup($query, 200, 220);
        $this->assertEquals(220, $results->getTotalItems());
        $this->assertCount(220, $results->getItems());

        // Check 180th Volume is same as the 80th of the second result set
        $volumes = $results->getItems();
        $this->assertEquals($volume_180th->getId(), $volumes[80]->getId());
    }

    public function testLookupByTitle()
    {
        $title = 'systems analysis and design for a changing world';

        // Retrieve volumes using the lookup manager
        $volumes = $this->volume_lookup_manager->lookupByTitle($title)->getItems();

        $query = new VolumeSearchQuery();
        $query->setTitle($title);
        $json_volumes = $this->callApi($query);

        foreach ($volumes as $k => $volume) {
            $this->annotation_mapper_test->testVolumeEntityMapping($json_volumes['items'][$k], $volume);
        }
    }

    public function testLookupByAuthor()
    {
        $author = 'John Satzinger';

        // Retrieve volumes using the lookup manager
        $volumes = $this->volume_lookup_manager->lookupByAuthor($author)->getItems();

        $query = new VolumeSearchQuery();
        $query->setAuthorName($author);
        $json_volumes = $this->callApi($query);

        foreach ($volumes as $k => $volume) {
            $this->annotation_mapper_test->testVolumeEntityMapping($json_volumes['items'][$k], $volume);
        }
    }

}
