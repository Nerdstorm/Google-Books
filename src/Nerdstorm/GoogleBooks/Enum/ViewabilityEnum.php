<?php

namespace Nerdstorm\GoogleBooks\Enum;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class ViewabilityEnum
 *
 * Possible values are PARTIAL, ALL_PAGES, NO_PAGES or UNKNOWN.
 *
 * This value depends on the country listed above. A value of PARTIAL means that the publisher has allowed
 * some portion of the volume to be viewed publicly, without purchase. This can apply to eBooks as
 * well as non-eBooks. Public domain books will always have a value of ALL_PAGES.
 */
final class ViewabilityEnum extends AbstractEnumeration
{
    const PARTIAL   = 'PARTIAL';
    const ALL_PAGES = 'ALL_PAGES';
    const NO_PAGES  = 'NO_PAGES';
    const UNKNOWN   = 'UNKNOWN';
}