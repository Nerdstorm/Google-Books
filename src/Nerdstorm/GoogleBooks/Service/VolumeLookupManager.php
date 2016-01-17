<?php

namespace Nerdstorm\GoogleBooks\Service;

use GuzzleHttp\Psr7\Response;
use Nerdstorm\GoogleBooks\Annotations\Mapper\AnnotationMapper;
use Nerdstorm\GoogleBooks\Api\VolumesSearch;
use Nerdstorm\GoogleBooks\Entity\Volume;
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
     * @param     $title
     * @param int $start
     * @param int $count Default: VolumesSearch::MAX_RESULTS
     *
     * @return Volumes
     */
    public function lookupByTitle($title, $start = 0, $count = VolumesSearch::MAX_RESULTS)
    {
        /** @var VolumeSearchQuery $query */
        $query = new VolumeSearchQuery();
        $query->setTitle($title);

        return $this->lookup($query, $start, $count);
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
            $volumes
                ->setItems(array_slice($volumes->getItems(), 0, $count))
                ->setTotalItems(count($volumes->getItems()))
            ;

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

        if (count($_volumes->getItems()) < VolumesSearch::MAX_RESULTS) {
            $volumes
                ->setItems(array_slice($volumes->getItems(), 0, $count))
                ->setTotalItems(count($volumes->getItems()))
            ;

            return $volumes;
        }

        return $this->lookup(
            $query, (int) $start_index, (int) $count, $order_by, $downloadable, $filter, $language,
            $print_type, $projection, $volumes
        );
    }

    /**
     * Find volumes in the Google Books database by the author name.
     *
     * @param string $author
     * @param int    $start
     * @param int    $count Default: VolumesSearch::MAX_RESULTS
     *
     * @return Volumes
     */
    public function lookupByAuthor($author, $start = 0, $count = VolumesSearch::MAX_RESULTS)
    {
        /** @var VolumeSearchQuery $query */
        $query = new VolumeSearchQuery();
        $query->setAuthorName($author);

        return $this->lookup($query, $start, $count);
    }

    /**
     * Find volumes in the Google Books database by the publish name.
     *
     * @param string $publisher
     * @param int    $start
     * @param int    $count Default: VolumesSearch::MAX_RESULTS
     *
     * @return Volumes
     */
    public function lookupByPublisher($publisher, $start = 0, $count = VolumesSearch::MAX_RESULTS)
    {
        /** @var VolumeSearchQuery $query */
        $query = new VolumeSearchQuery();
        $query->setPublisher($publisher);

        return $this->lookup($query, $start, $count);
    }

    /**
     * Find volumes filtered by the subject.
     *
     * @param string $subject
     * @param int    $start
     * @param int    $count
     *
     * @return Volumes
     */
    public function lookupBySubject($subject, $start = 0, $count = VolumesSearch::MAX_RESULTS)
    {
        /** @var VolumeSearchQuery $query */
        $query = new VolumeSearchQuery();
        $query->setSubject($subject);

        return $this->lookup($query, $start, $count);
    }

    /**
     * Find a volume by an ISBN.
     *
     * @param string $isbn
     * @return Volume|null
     */
    public function findByISBN($isbn)
    {
        /** @var VolumeSearchQuery $query */
        $query = new VolumeSearchQuery();
        $query->setIsbn($isbn);

        $volumes = $this->lookup($query);
        if ($volumes->getTotalItems()) {
            return $volumes->getItems()[0];
        }

        return null;
    }

}