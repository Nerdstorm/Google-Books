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
     * @param int                      $start_index
     * @param int                      $count
     * @param OrderByEnum|null         $order_by
     * @param bool                     $downloadable
     * @param VolumeFilterEnum|null    $filter
     * @param string                   $language
     * @param PublicationTypeEnum|null $print_type
     * @param ProjectionEnum|null      $projection
     * @param Volumes                  $volumes
     *
     * @return array|\Nerdstorm\GoogleBooks\Entity\EntityInterface
     * @throws \Nerdstorm\GoogleBooks\Exception\InvalidJsonException
     */
    public function lookup(VolumeSearchQuery $query, $start_index = 0, $count = VolumesSearch::MAX_RESULTS,
        OrderByEnum $order_by = null, $downloadable = null, VolumeFilterEnum $filter = null, $language = null,
        PublicationTypeEnum $print_type = null, ProjectionEnum $projection = null, Volumes $volumes = null)
    {
        if (null === $volumes) {
            $volumes = new Volumes();
        } elseif (count($volumes->getItems()) >= $count) {
            $volumes->setItems(array_chunk($volumes->getItems(), $count));
            $volumes->setTotalItems(count($volumes->getItems()));

            return $volumes;
        }

        /** @var Response $response */
        $response = $this->volume_search->volumesList(
            $query, $downloadable, $filter, $language, $start_index, VolumesSearch::MAX_RESULTS, $order_by, $print_type,
            $projection
        );

        $json_object = json_decode((string) $response->getBody(), true);

        /** @var Volumes $results */
        $_volumes = $this->annotation_mapper->resolveEntity($json_object);

        /**
         * Google's trick of first best-guessing the total results for a query.
         * https://productforums.google.com/forum/#!topic/books-api/Y_uEJhohJCc
         *
         * Therefore, we assume Google only got only whatever number of results based on its very first response.
         */
        if (!$_volumes->getTotalItems()) {
            return $volumes;
        }

        $volumes->setItems($volumes->getItems() + $_volumes->getItems());
        $start_index += (int) VolumesSearch::MAX_RESULTS;

        return $this->lookup(
            $query, (int) $start_index, (int) $count, $order_by, $downloadable, $filter, $language,
            $print_type, $projection, $volumes
        );
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