<?php

namespace Nerdstorm\GoogleBooks\Enum;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class SaleabilityEnum
 *
 * Whether or not this book is available for sale or offered for free in the Google eBookstore
 * for the country listed above. Possible values are FOR_SALE, FREE, NOT_FOR_SALE, or FOR_PREORDER.
 *
 */
final class SaleabilityEnum extends AbstractEnumeration
{
    const FOR_SALE     = 'FOR_SALE';
    const FREE         = 'FREE';
    const NOT_FOR_SALE = 'NOT_FOR_SALE';
    const FOR_PREORDER = 'FOR_PREORDER';
}