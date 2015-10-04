<?php

namespace Nerdstorm\GoogleBooks\Enum;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class ProjectionEnum
 *
 *  "full" - Includes all volume data.
 *  "lite" - Includes a subset of fields in volumeInfo and accessInfo.
 */
final class ProjectionEnum extends AbstractEnumeration
{
    const FULL = 'full';
    const LITE = 'lite';
}