<?php

namespace Nerdstorm\GoogleBooks\Api;

use Nerdstorm\GoogleBooks\Entity\Volume;
use Nerdstorm\GoogleBooks\Entity\Volumes;
use Nerdstorm\GoogleBooks\Enum\ProjectionEnum;

class VolumesSearch extends BooksBase
{

    /**
     * Performs a book search.
     *
     * @param string         $q Full-text search query string
     * @param string         $download Restrict to volumes by download availability.
     * @param string         $filter Filter search results.
     *  Acceptable values are:
     *      "ebooks" - All Google eBooks.
     *      "free-ebooks" - Google eBook with full volume text viewability.
     *      "full" - Public can view entire volume text.
     *      "paid-ebooks" - Google eBook with a price.
     *      "partial" - Public able to see parts of text.
     * @param string         $lang_restrict Restrict results to books with this language code.
     * @param string         $library_restrict Restrict search to this user's library.
     *  Acceptable values are:
     *      "my-library" - Restrict to the user's library, any shelf.
     *      "no-restrict" - Do not restrict based on user's library.
     * @param int            $max_results Maximum number of results to return. Acceptable values are 0 to 40, inclusive.
     * @param string         $order_by Sort search results.
     *  Acceptable values are:
     *      "newest" - Most recently published.
     *      "relevance" - Relevance to search terms.
     * @param string         $partner Restrict and brand results for partner ID.
     * @param string         $print_type Restrict to books or magazines.
     *  Acceptable values are:
     *      "all" - All volume content types.
     *      "books" - Just books.
     *      "magazines" - Just magazines.
     * @param ProjectionEnum $projection Restrict information returned to a set of selected fields.
     *  Acceptable values are:
     *      "full" - Includes all volume data.
     *      "lite" - Includes a subset of fields in volumeInfo and accessInfo.
     * @param bool           $show_preorders Set to true to show books available for preorder. Defaults to false.
     * @param string         $source String to identify the originator of this request.
     * @param int            $start_index Index of the first result to return (starts at 0)
     *
     * @return Volumes
     */
    public function volumesList($q, $download = null, $filter = null, $lang_restrict = null, $library_restrict = null,
                                $max_results = null, $order_by = null, $partner = null, $print_type = null,
                                ProjectionEnum $projection = null, $show_preorders = false, $source = null,
                                $start_index = null)
    {

    }

    /**
     * Retrieves a Volume resource based on ID.
     *
     * Unique strings given to each volume that Google Books knows about. An example of a volume ID is _LettPDhwR0C.
     * You can use the API to get the volume ID by making a request that returns a Volume resource; you can find the
     * volume ID in its id field.
     *
     * @param                $volume_id
     * @param string         $partner
     * @param ProjectionEnum $projection
     * @param string         $source
     *
     * @return Volume
     */
    public function volumesGet($volume_id, $partner = null, ProjectionEnum $projection = ProjectionEnum::LITE,
                               $source = null)
    {

    }
}