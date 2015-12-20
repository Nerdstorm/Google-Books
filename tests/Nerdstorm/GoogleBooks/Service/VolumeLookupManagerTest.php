<?php

namespace tests\Nerdstorm\GoogleBooks\Service;

use Nerdstorm\GoogleBooks\Service\VolumeLookupManager;
use tests\Nerdstorm\Config;

class VolumeLookupManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var VolumeLookupManager */
    protected $volume_lookup_manager;

    public function setup()
    {
        $config = ['api_key' => Config::API_KEY] + Config::guzzleOpts();

        /** @var VolumeLookupManager volume_lookup_manager */
        $this->volume_lookup_manager = new VolumeLookupManager($config);
    }

    public function testLookupByTitle()
    {
        $volumes = $this->volume_lookup_manager->lookupByTitle('systems analysis and design');
        var_dump($volumes);
        $this->assertTrue(true);
    }
}
