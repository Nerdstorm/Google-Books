<?php

namespace Nerdstorm\GoogleBooks\Enum;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class PublicationTypeEnum
 *
 * You can use the printType parameter to restrict the returned results to a specific print or publication type by
 * setting it to one of the following values:
 *
 *  "all" - Does not restrict by print type (default).
 *  "books" - Returns only results that are books.
 *  "magazines" - Returns results that are magazines.
 *
 * The following example restricts search results to magazines:
 */
final class PublicationTypeEnum extends AbstractEnumeration
{
    const ALL       = 'all';
    const BOOKS     = 'books';
    const MAGAZINES = 'magazines';
}