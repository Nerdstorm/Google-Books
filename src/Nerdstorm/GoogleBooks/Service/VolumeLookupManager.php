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
     * @param VolumesSearch $volume_search VolumeSearch service required to communicate with Google Books API endpoint.
     */
    public function __construct(VolumesSearch $volume_search)
    {
        $this->volume_search     = $volume_search;
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
     * Lookup volumes based on the provided parameters. Returns a Volumes object.
     * Function calls the API recursively to retrieve all volumes based on the query parameters.
     *
     * The recursiveness of the function depend on the $count parameter as API only returns a maximum of 40 results
     * at once, for ex: using a 80 as $count would recursively call the API twice. Therefore the value of the
     * $count variable contribute to how long this function would run.
     *
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
     * @return Volumes
     * @throws \Nerdstorm\GoogleBooks\Exception\InvalidJsonException
     */
    public function lookup(VolumeSearchQuery $query, $start_index = 0, $count = VolumesSearch::MAX_RESULTS,
        OrderByEnum $order_by = null, $downloadable = null, VolumeFilterEnum $filter = null, $language = null,
        PublicationTypeEnum $print_type = null, ProjectionEnum $projection = null, Volumes $volumes = null)
    {
        if (null === $volumes) {
            $volumes = new Volumes();
        } elseif (count($volumes->getItems()) >= $count) {
            $volumes->setItems(array_slice($volumes->getItems(), 0, $count));
            $volumes->setTotalItems($count);

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
        $volumes->addItems($_volumes->getItems());
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