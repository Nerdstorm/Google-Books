<?php

namespace tests\Nerdstorm\GoogleBooks\Service;

use GuzzleHttp\Psr7\Response;
use Nerdstorm\GoogleBooks\Api\VolumesSearch;
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

        $config = ['api_key' => Config::API_KEY] + Config::guzzleOpts();

        /** @var VolumeLookupManager volume_lookup_manager */
        $this->volume_lookup_manager  = new VolumeLookupManager($config);
        $this->annotation_mapper_test = new AnnotationMapperTest();
    }

    public function testLookupByTitle()
    {
        $title = 'systems analysis and design for a changing world';
        $query = new VolumeSearchQuery();
        $query->setTitle($title);

        // Retrieve volumes using the lookup manager
        $volumes      = $this->volume_lookup_manager->lookupByTitle($title)->getItems();
        $json_volumes = $this->callApi($query);

        foreach ($volumes as $k => $volume) {
            $this->annotation_mapper_test->testVolumeEntityMapping($json_volumes['items'][$k], $volume);
        }
    }

    public function testLookupByAuthor()
    {
        $author = 'John Satzinger';

        // Retrieve volumes using the lookup manager
        $volumes      = $this->volume_lookup_manager->lookupByAuthor($author)->getItems();
        $json_volumes = $this->callApi(new VolumeSearchQuery($author));

        foreach ($volumes as $k => $volume) {
            $this->annotation_mapper_test->testVolumeEntityMapping($json_volumes['items'][$k], $volume);
        }
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

}
