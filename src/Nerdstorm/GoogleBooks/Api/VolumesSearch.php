<?php

namespace Nerdstorm\GoogleBooks\Api;

use Nerdstorm\GoogleBooks\Entity\Volume;
use Nerdstorm\GoogleBooks\Entity\Volumes;
use Nerdstorm\GoogleBooks\Enum\OrderByEnum;
use Nerdstorm\GoogleBooks\Enum\ProjectionEnum;
use Nerdstorm\GoogleBooks\Enum\PublicationTypeEnum;
use Nerdstorm\GoogleBooks\Enum\VolumeFilterEnum;
use Nerdstorm\GoogleBooks\Exception\ArgumentOutOfBoundsException;
use Nerdstorm\GoogleBooks\Query\QueryInterface;

class VolumesSearch extends AbstractSearchBase
{

    /**
     * Performs a book search.
     *
     * @param QueryInterface      $q              Full-text Search query object
     * @param bool                $download       Restrict to volumes by download availability.
     * @param VolumeFilterEnum    $filter         Filter search results.
     *                                            Acceptable values are:
     *                                            "ebooks" - All Google eBooks.
     *                                            "free-ebooks" - Google eBook with full volume text viewability.
     *                                            "full" - Public can view entire volume text.
     *                                            "paid-ebooks" - Google eBook with a price.
     *                                            "partial" - Public able to see parts of text.
     * @param string              $lang_restrict  Restrict results to books with this language code. ISO-639-1 code.
     * @param int                 $start_index    Index of the first result to return (starts at 0)
     * @param int                 $max_results    Maximum number of results to return. Acceptable values are 0 to 40,
     *                                            inclusive. Default is 10.
     * @param OrderByEnum         $order_by       Sort search results.
     *                                            Acceptable values are:
     *                                            "newest" - Most recently published.
     *                                            "relevance" - Relevance to search terms.
     * @param PublicationTypeEnum $print_type     Restrict to books or magazines.
     *                                            Acceptable values are:
     *                                            "all" - All volume content types.
     *                                            "books" - Just books.
     *                                            "magazines" - Just magazines.
     * @param ProjectionEnum      $projection     Restrict information returned to a set of selected fields.
     *                                            Acceptable values are:
     *                                            "full" - Includes all volume data.
     *                                            "lite" - Includes a subset of fields in volumeInfo and accessInfo.
     *
     * @return Volumes
     */
    public function volumesList(QueryInterface $q, $download = false, VolumeFilterEnum $filter = null,
        $lang_restrict = null, $start_index = 0, $max_results = 10, OrderByEnum $order_by = null,
        PublicationTypeEnum $print_type = null, ProjectionEnum $projection = null)
    {
        $api_method = 'volumes/';
        $query = [];
        $query['q'] = (string) $q;

        // Google Books API only filters based on downloadable epub content.
        if (true === $download) {
            $query['download'] = 'epub';
        }

        // Filter types of volumes
        if ($filter instanceof VolumeFilterEnum) {
            $query['filter'] = $filter->value();
        }

        // ISO-639-1 specification require language code to be in 2-character presentation
        if (strlen($lang_restrict) == 2) {
            $query['langRestrict'] = $lang_restrict;
        } elseif (null !== $lang_restrict) {
            throw new ArgumentOutOfBoundsException('Language restriction has to be in ISO-639-1 standard.');
        }

        // Number of volume matches to return in a single response
        if ($max_results < 0 or $max_results > 40) {
            throw new ArgumentOutOfBoundsException('Max results has to be a value between 0 and 40');
        } else {
            $query['maxResults'] = $max_results;
        }

        // Ordering of results
        if (null === $order_by) {
            $query['orderBy'] = OrderByEnum::RELEVANCE()->value();
        } else {
            $query['orderBy'] = $order_by->value();
        }

        // Publication / Print type
        if (null === $print_type) {
            $query['printType'] = PublicationTypeEnum::ALL()->value();
        } else {
            $query['printType'] = $print_type->value();
        }

        // Projection
        if ($projection) {
            $query['projection'] = $projection->value();
        }

        // Start index (cursor) for results
        if ($start_index < 0) {
            throw new ArgumentOutOfBoundsException('Start index cannot be a negative number');
        } else {
            $query['startIndex'] = (int) $start_index;
        }

        return $this->send('get', $api_method, ['query' => $query]);
    }

    /**
     * Retrieves a Volume resource based on ID.
     *
     * Unique strings given to each volume that Google Books knows about. An example of a volume ID is _LettPDhwR0C.
     * You can use the API to get the volume ID by making a request that returns a Volume resource; you can find the
     * volume ID in its id field.
     *
     * @param string         $volume_id
     * @param string         $partner
     * @param ProjectionEnum $projection
     * @param string         $source
     *
     * @return Volume
     */
    public function volumeGet($volume_id, $partner = null, ProjectionEnum $projection = ProjectionEnum::LITE,
        $source = null)
    {

    }
}