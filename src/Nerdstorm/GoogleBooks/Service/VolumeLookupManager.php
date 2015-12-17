<?php

namespace Nerdstorm\GoogleBooks\Service;

class VolumeLookupManager
{

    /**
     * Find volumes in the Google Books database by their title.
     * Title is a string which will be used for partially match volumes.
     *
     * @param string $title
     *
     * @return array
     */
    public function getVolumesByTitle($title)
    {
        $results = [];

        return $results;
    }
}