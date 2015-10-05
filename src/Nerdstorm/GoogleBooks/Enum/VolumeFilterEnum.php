<?php

namespace Nerdstorm\GoogleBooks\Enum;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class VolumeFilterEnum
 *
 * Allowed values: [ebooks, free-ebooks, full, paid-ebooks, partial]
 */
final class VolumeFilterEnum extends AbstractEnumeration
{
    const EBOOKS      = 'ebooks';
    const FULL        = 'full';
    const PARTIAL     = 'partial';
    const PAID_EBOOKS = 'paid-ebooks';
    const FREE_EBOOKS = 'free-ebooks';
}
