<?php

namespace Nerdstorm\GoogleBooks\Entity;

/**
 * Class Volumes
 *
 * The volumess collection allows you to retrieve volumes from Google Books.
 */
class Volumes
{
    /**
     * Number of book volumes found.
     *
     * @var int
     */
    protected $total_items;

    /**
     * Array of book volume objects.
     *
     * @var Volume[]
     */
    protected $items;

    /**
     * @return int
     */
    public function getTotalItems()
    {
        return $this->total_items;
    }

    /**
     * @param int $total_items
     *
     * @return Volumes
     */
    public function setTotalItems($total_items)
    {
        $this->total_items = $total_items;

        return $this;
    }

    /**
     * @return Volume[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Volume[] $items
     *
     * @return Volumes
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

}