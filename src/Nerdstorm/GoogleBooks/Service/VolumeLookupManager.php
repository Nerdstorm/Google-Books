<?php

namespace Nerdstorm\GoogleBooks\Service;

use GuzzleHttp\Psr7\Response;
use Nerdstorm\GoogleBooks\Annotations\Mapper\AnnotationMapper;
use Nerdstorm\GoogleBooks\Api\VolumesSearch;
use Nerdstorm\GoogleBooks\Entity\Volumes;
use Nerdstorm\GoogleBooks\Enum\OrderByEnum;
use Nerdstorm\GoogleBooks\Enum\ProjectionEnum;
use Nerdstorm\GoogleBooks\Enum\PublicationTypeEnum;
use Nerdstorm\GoogleBooks\Enum\VolumeFilterEnum;
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
     * @return Volumes
     */
    public function lookupByTitle($title, $start = 0, $limit = 10)
    {
        /** @var VolumeSearchQuery $query */
        $query = new VolumeSearchQuery();
        $query->setTitle($title);

        return $this->lookup($query, $start, $limit);
    }

    /**
     * @param VolumeSearchQuery        $query
     * @param int                      $start
     * @param int                      $limit
     * @param OrderByEnum|null         $order_by
     * @param null                     $downloadable
     * @param VolumeFilterEnum|null    $filter
     * @param null                     $language
     * @param PublicationTypeEnum|null $print_type
     * @param ProjectionEnum|null      $projection
     * @param array                    $volumes
     *
     * @return array|\Nerdstorm\GoogleBooks\Entity\EntityInterface
     * @throws \Nerdstorm\GoogleBooks\Exception\InvalidJsonException
     */
    protected function lookup(VolumeSearchQuery $query, $start = 0, $limit = 10, OrderByEnum $order_by = null,
        $downloadable = null, VolumeFilterEnum $filter = null, $language = null,
        PublicationTypeEnum $print_type = null, ProjectionEnum $projection = null, array $volumes)
    {
        $_limit = 0;
        $_start = $start;

        if ($limit <= VolumesSearch::MAX_RESULTS) {
            $_limit = $limit;
        } elseif (floor($limit / VolumesSearch::MAX_RESULTS) > 0) {
            $_limit = $limit - VolumesSearch::MAX_RESULTS;
        }

        if ($_limit <= VolumesSearch::MAX_RESULTS) {
            /** @var Response $response */
            $response = $this->volume_search->volumesList(
                $query, $downloadable, $filter, $language, $start, $_limit, $order_by, $print_type, $projection
            );

            $json_object = json_decode((string) $response->getBody(), true);
            $volumes += $this->annotation_mapper->resolveEntity($json_object);
        } else {
            $volumes += $this->lookup(
                $query, $start, $_limit, $order_by, $downloadable, $filter, $language, $start, $_limit, $order_by,
                $print_type, $projection, $volumes
            );
        }

        return $volumes;
    }

    /**
     * Find volumes in the Google Books database by their title.
     * Title is a string which will be used for partially match volumes.
     *
     * TODO: Make this accept start_index and limit, if both not set, return all results recursively requesting data
     *
     * @param string $title
     *
     * @return Volumes
     */
    public function lookupByAuthor($author)
    {
        /** @var VolumeSearchQuery $query */
        $query = new VolumeSearchQuery();
        $query->setAuthorName($author);

        /** @var Response $response */
        $response    = $this->volume_search->volumesList($query);
        $json_object = json_decode((string) $response->getBody(), true);
        $volumes     = $this->annotation_mapper->resolveEntity($json_object);

        return $volumes;
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