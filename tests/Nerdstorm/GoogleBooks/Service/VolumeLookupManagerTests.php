<?php

namespace tests\Nerdstorm\GoogleBooks\Api;

use GuzzleHttp\Psr7\Response;
use Nerdstorm\GoogleBooks\Api\VolumesSearch;

class VolumeSearchTest extends \PHPUnit_Framework_TestCase
{
    /** @var VolumeSearch */
    protected $volume_search;

    public function setup()
    {
        /** @var VolumesSearch volume_search */
        $this->volume_search = new VolumesSearch(
            Config::API_KEY,
            Config::guzzleOpts()
        );
    }

}
