<?php

namespace tests\Nerdstorm\GoogleBooks\Api;

use Nerdstorm\GoogleBooks\Api\VolumesSearch;

class VolumeSearchTest extends PHPUnit_Framework_TestCase
{
    /** @var VolumeSearch */
    protected $volume_search;

    public function setup()
    {
        $this->volume_search = new VolumesSearch();
    }

    public function testVolumeList()
    {

        $this->volume_search->volumesList();
    }
}