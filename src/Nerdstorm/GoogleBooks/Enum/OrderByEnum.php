<?php

namespace Nerdstorm\GoogleBooks\Enum;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class OrderByEnum
 *
 * You can change the ordering by setting the orderBy parameter to be one of these values:
 *
 *  "relevance" - Returns results in order of the relevance of search terms (this is the default).
 *  "newest" - Returns results in order of most recently to least recently published.
 */
final class OrderByEnum extends AbstractEnumeration
{
    const RELEVANCE = 'relevance';
    const NEWEST = 'newest';
}