<?php

namespace Nerdstorm\GoogleBooks\Enum;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class AccessViewStatusEnum
 *
 * VolumeInfo.accessViewStatus Combines the access and viewability of this volume
 * into a single status field for this user. Values can be FULL_PURCHASED,
 * FULL_PUBLIC_DOMAIN, SAMPLE or NONE. (In LITE projection.)
 */
final class AccessViewStatusEnum extends AbstractEnumeration
{
    const FULL_PURCHASED     = 'FULL_PURCHASED';
    const FULL_PUBLIC_DOMAIN = 'FULL_PUBLIC_DOMAIN';
    const SAMPLE             = 'SAMPLE';
    const NONE               = 'NONE';
}