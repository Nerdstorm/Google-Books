<?php

namespace Nerdstorm\GoogleBooks\Service;

use GuzzleHttp\Psr7\Response;
use Nerdstorm\GoogleBooks\Annotations\Mapper\AnnotationMapper;
use Nerdstorm\GoogleBooks\Api\VolumesSearch;
use Nerdstorm\GoogleBooks\Query\QueryInterface;
use Nerdstorm\GoogleBooks\Query\VolumeSearchQuery;

class VolumeLookupManager
{
    /** @var VolumesSearch */
    protected $volume_search;

    /** @var AnnotationMapper */
    protected $annotation_mapper;

    /**
     * VolumeLookupManager constructor.
     *
     * @param array $config Configuration required for setting up lookup manager to communicate with Google Books
     *                      API endpoint.
     */
    public function __construct(array $config)
    {
        if (empty($config['api_key'])) {
            throw new \RuntimeException('GoogleBooks API key not defined');
        }

        $api_key = $config['api_key'];
        unset($config['api_key']);

        $this->volume_search     = new VolumesSearch($api_key, $config);
        $this->annotation_mapper = new AnnotationMapper();
    }

    /**
     * Find volumes in the Google Books database by their title.
     * Title is a string which will be used for partially match volumes.
     *
     * @param string $title
     *
     * @return array
     */
    public function lookupByTitle($title)
    {
        $results = [];

        /** @var QueryInterface $query */
        $query = new VolumeSearchQuery($title);

        /** @var Response $response */
        $response = $this->volume_search->volumesList($query);

        $json        = (string) $response->getBody();
        $json_object = json_decode($json, true);


        $volumes = $this->annotation_mapper->resolveEntity($json_object['kind']);
//        unset($json_obj['kind']);


        $this->annotation_mapper->map($volumes, $json_object);

        return $results;
    }

    public function lookupByAuthor($author)
    {
        $results = [];

        return $results;
    }

    public function lookupByPublisher($publisher)
    {
        $results = [];

        return $results;
    }

    public function lookupByCategory($category)
    {
        $results = [];

        return $results;
    }

    public function lookupByISBN($isbn)
    {
        $results = [];

        return $results;
    }

    public function lookupByLCCN($lccn)
    {
        $results = [];

        return $results;
    }

    public function lookupByOCLC($lccn)
    {
        $results = [];

        return $results;
    }
}